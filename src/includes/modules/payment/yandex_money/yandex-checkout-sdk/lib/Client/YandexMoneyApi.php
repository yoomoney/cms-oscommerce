<?php

namespace YaMoney\Client;

use Psr\Log\LoggerInterface;
use YaMoney\Common\Exceptions\ApiException;
use YaMoney\Common\Exceptions\BadApiRequestException;
use YaMoney\Common\Exceptions\ForbiddenException;
use YaMoney\Common\Exceptions\JsonException;
use YaMoney\Common\Exceptions\InternalServerError;
use YaMoney\Common\Exceptions\NotFoundException;
use YaMoney\Common\Exceptions\TooManyRequestsException;
use YaMoney\Common\Exceptions\UnauthorizedException;
use YaMoney\Common\HttpVerb;
use YaMoney\Common\LoggerWrapper;
use YaMoney\Common\ResponseObject;
use YaMoney\Helpers\Config\ConfigurationLoader;
use YaMoney\Helpers\Config\ConfigurationLoaderInterface;
use YaMoney\Request\PaymentOptionsRequestInterface;
use YaMoney\Request\PaymentOptionsRequestSerializer;
use YaMoney\Request\PaymentOptionsResponse;
use YaMoney\Request\Payments\CreatePaymentRequestInterface;
use YaMoney\Request\Payments\CreatePaymentResponse;
use YaMoney\Request\Payments\CreatePaymentRequestSerializer;
use YaMoney\Request\Payments\Payment\CancelResponse;
use YaMoney\Request\Payments\Payment\CreateCaptureRequestInterface;
use YaMoney\Request\Payments\Payment\CreateCaptureRequestSerializer;
use YaMoney\Request\Payments\Payment\CreateCaptureResponse;
use YaMoney\Request\Payments\PaymentResponse;
use YaMoney\Request\Payments\PaymentsRequestInterface;
use YaMoney\Request\Payments\PaymentsRequestSerializer;
use YaMoney\Request\Payments\PaymentsResponse;
use YaMoney\Request\Refunds\CreateRefundRequestInterface;
use YaMoney\Request\Refunds\CreateRefundRequestSerializer;
use YaMoney\Request\Refunds\CreateRefundResponse;
use YaMoney\Request\Refunds\RefundResponse;
use YaMoney\Request\Refunds\RefundsRequestInterface;
use YaMoney\Request\Refunds\RefundsRequestSerializer;
use YaMoney\Request\Refunds\RefundsResponse;

class YandexMoneyApi
{
    const IDEMPOTENCY_KEY_HEADER = 'Idempotence-Key';

    /**
     * @var null|ApiClientInterface
     */
    protected $apiClient;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $config;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * Constructor
     *
     * @param ApiClientInterface|null $apiClient
     * @param ConfigurationLoaderInterface|null $configLoader
     * @internal-param null|ConfigurationLoader $config
     */
    public function __construct(ApiClientInterface $apiClient = null, ConfigurationLoaderInterface $configLoader = null)
    {
        if ($apiClient === null) {
            $apiClient = new CurlClient();
        }

        if ($configLoader === null) {
            $configLoader = new ConfigurationLoader();
            $config = $configLoader->load()->getConfig();
            $this->setConfig($config);
            $apiClient->setConfig($config);
        }

        $this->apiClient = $apiClient;
    }

    /**
     * @param $login
     * @param $password
     * @return YandexMoneyApi $this
     */
    public function setAuth($login, $password)
    {
        $this->login = $login;
        $this->password = $password;

        $this->apiClient
            ->setShopId($this->login)
            ->setShopPassword($this->password);

        return $this;
    }

    /**
     * @return ApiClientInterface
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * @param ApiClientInterface $apiClient
     *
     * @return YandexMoneyApi
     */
    public function setApiClient(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->apiClient->setConfig($this->config);
        $this->apiClient->setLogger($this->logger);

        return $this;
    }

    /**
     * Устанавливает логгер приложения
     * @param null|callable|object|LoggerInterface $value Инстанс логгера
     */
    public function setLogger($value)
    {
        if ($value === null || $value instanceof LoggerInterface) {
            $this->logger = $value;
        } else {
            $this->logger = new LoggerWrapper($value);
        }
        if ($this->apiClient !== null) {
            $this->apiClient->setLogger($this->logger);
        }
    }

    /**
     * Доступные способы оплаты.
     * Используйте этот метод, чтобы получить способы оплаты и сценарии, доступные для вашего заказа.
     * @param PaymentOptionsRequestInterface $paymentOptionsRequest
     * @return PaymentOptionsResponse
     */
    public function getPaymentOptions(PaymentOptionsRequestInterface $paymentOptionsRequest = null)
    {
        $path = "/payment_options";

        if ($paymentOptionsRequest === null) {
            $queryParams = array();
        } else {
            $serializer = new PaymentOptionsRequestSerializer();
            $serializedData = $serializer->serialize($paymentOptionsRequest);
            $queryParams = $serializedData;
        }

        $response = $this->apiClient->call($path, HttpVerb::GET, $queryParams);

        $result = null;
        if ($response->getCode() == 200) {
            $responseArray = $this->decodeData($response);
            $result = new PaymentOptionsResponse($responseArray);
        } else {
            $this->handleError($response);
        }
        return $result;
    }

    /**
     * Получить список платежей магазина.
     * @param PaymentsRequestInterface $payments
     * @return PaymentsResponse
     */
    public function getPayments(PaymentsRequestInterface $payments = null)
    {
        $path = '/payments';

        if ($payments) {
            $serializer = new PaymentsRequestSerializer();
            $serializedData = $serializer->serialize($payments);
            $queryParams = $serializedData;
        } else {
            $queryParams = array();
        }

        $response = $this->apiClient->call($path, HttpVerb::GET, $queryParams);
        $paymentResponse = null;
        if ($response->getCode() == 200) {
            $responseArray = $this->decodeData($response);
            $paymentResponse = new PaymentsResponse($responseArray);
        } else {
            $this->handleError($response);
        }
        return $paymentResponse;
    }

    /**
     * Создание платежа.
     *
     * Чтобы принять оплату, необходимо создать объект платежа — `Payment`. Он содержит всю необходимую информацию
     * для проведения оплаты (сумму, валюту и статус). У платежа линейный жизненный цикл, он последовательно
     * переходит из статуса в статус.
     *
     * Необходимо указать один из параметров:
     * <ul>
     * <li>payment_token — оплата по одноразовому PaymentToken, сформированному виджетом Yandex.Checkout JS;</li>
     * <li>payment_method_id — оплата по сохраненным платежным данным;</li>
     * <li>payment_method_data — оплата по новым платежным данным.</li>
     * </ul>
     *
     * Если не указан ни один параметр и `confirmation.type = redirect`, то в качестве `confirmation_url`
     * возвращается ссылка, по которой пользователь сможет самостоятельно выбрать подходящий способ оплаты.
     * Дополнительные параметры:
     * <ul>
     * <li>confirmation — передается, если необходимо уточнить способ подтверждения платежа;</li>
     * <li>recipient — указывается при наличии нескольких товаров;</li>
     * <li>metadata — дополнительные данные (передаются магазином).</li>
     * </ul>
     *
     * @param CreatePaymentRequestInterface $payment
     * @param string|null $idempotencyKey
     *
     * @return CreatePaymentResponse
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws UnauthorizedException
     */
    public function createPayment(CreatePaymentRequestInterface $payment, $idempotencyKey = null)
    {
        $path = '/payments';

        $headers = array();

        if ($idempotencyKey) {
            $headers[self::IDEMPOTENCY_KEY_HEADER] = $idempotencyKey;
        }

        $serializer = new CreatePaymentRequestSerializer();
        $serializedData = $serializer->serialize($payment);
        $httpBody = $this->encodeData($serializedData);

        $response = $this->apiClient->call($path, HttpVerb::POST, null, $httpBody, $headers);
        $paymentResponse = null;
        if ($response->getCode() == 200) {
            $resultArray = $this->decodeData($response);
            $paymentResponse = new CreatePaymentResponse($resultArray);
        } else {
            $this->handleError($response);
        }
        return $paymentResponse;
    }

    /**
     * Получить информацию о платеже
     *
     * Выдает объект платежа {@link PaymentInterface} по его уникальному идентификатору.
     *
     * @param $paymentId
     * @return PaymentResponse
     */
    public function getPaymentInfo($paymentId)
    {
        if ($paymentId === null) {
            throw new \InvalidArgumentException('Missing the required parameter $paymentId');
        }

        $path = '/payments/' . $paymentId;

        $response = $this->apiClient->call($path, HttpVerb::GET, null);
        $result = null;
        if ($response->getCode() == 200) {
            $resultArray = $this->decodeData($response);
            $result = new PaymentResponse($resultArray);
        } else {
            $this->handleError($response);
        }
        return $result;
    }

    /**
     * Подтверждение платежа
     *
     * Подтверждает вашу готовность принять платеж. Платеж можно подтвердить, только если он находится
     * в статусе `waiting_for_capture`. Если платеж подтвержден успешно — значит, оплата прошла, и вы можете выдать
     * товар или оказать услугу пользователю. На следующий день после подтверждения платеж попадет в реестр,
     * и Яндекс.Касса переведет деньги на ваш расчетный счет. Если вы не подтверждаете платеж до момента, указанного
     * в `expire_at`, по умолчанию он отменяется, а деньги возвращаются пользователю. При оплате банковской картой
     * у вас есть 7 дней на подтверждение платежа. Для остальных способов оплаты платеж необходимо подтвердить
     * в течение 6 часов.
     *
     * @param CreateCaptureRequestInterface $captureRequest
     * @param $paymentId
     * @param null $idempotencyKey
     * @return CreateCaptureResponse
     */
    public function capturePayment(CreateCaptureRequestInterface $captureRequest, $paymentId, $idempotencyKey = null)
    {
        if ($paymentId === null) {
            throw new \InvalidArgumentException('Missing the required parameter $paymentId');
        }

        $path = '/payments/' . $paymentId . '/capture';

        $headers = array();

        if ($idempotencyKey) {
            $headers[self::IDEMPOTENCY_KEY_HEADER] = $idempotencyKey;
        }

        $serializer = new CreateCaptureRequestSerializer();
        $serializedData = $serializer->serialize($captureRequest);
        $httpBody = $this->encodeData($serializedData);
        $response = $this->apiClient->call($path, HttpVerb::POST, null, $httpBody, $headers);

        $result = null;
        if ($response->getCode() == 200) {
            $resultArray = $this->decodeData($response);
            $result = new CreateCaptureResponse($resultArray);
        } else {
            $this->handleError($response);
        }
        return $result;
    }

    /**
     * Отменить незавершенную оплату заказа.
     *
     * Отменяет платеж, находящийся в статусе `waiting_for_capture`. Отмена платежа значит, что вы
     * не готовы выдать пользователю товар или оказать услугу. Как только вы отменяете платеж, мы начинаем
     * возвращать деньги на счет плательщика. Для платежей банковскими картами отмена происходит мгновенно.
     * Для остальных способов оплаты возврат может занимать до нескольких дней.
     *
     * @param $paymentId
     * @param null $idempotencyKey
     * @return CancelResponse
     */
    public function cancelPayment($paymentId, $idempotencyKey = null)
    {
        if ($paymentId === null) {
            throw new \InvalidArgumentException('Missing the required parameter $paymentId');
        }

        $path = '/payments/' . $paymentId . '/cancel';

        $headers = array();

        if ($idempotencyKey) {
            $headers[self::IDEMPOTENCY_KEY_HEADER] = $idempotencyKey;
        }

        $response = $this->apiClient->call($path, HttpVerb::POST, null, null, $headers);

        $result = null;
        if ($response->getCode() == 200) {
            $resultArray = $this->decodeData($response);
            $result = new CancelResponse($resultArray);
        } else {
            $this->handleError($response);
        }
        return $result;
    }

    /**
     * Получить список возвратов платежей
     * @param RefundsRequestInterface $refundsRequest
     * @return RefundsResponse
     */
    public function getRefunds(RefundsRequestInterface $refundsRequest = null)
    {
        $path = '/refunds';

        if ($refundsRequest) {
            $serializer = new RefundsRequestSerializer();
            $serializedData = $serializer->serialize($refundsRequest);

            $queryParams = $serializedData;
        } else {
            $queryParams = array();
        }

        $response = $this->apiClient->call($path, HttpVerb::GET, $queryParams);
        $refundsResponse = null;
        if ($response->getCode() == 200) {
            $resultArray = $this->decodeData($response);
            $refundsResponse = new RefundsResponse($resultArray);
        } else {
            $this->handleError($response);
        }
        return $refundsResponse;
    }

    /**
     * Проведение возврата платежа
     *
     * Создает объект возврата — `Refund`. Возвращает успешно завершенный платеж по уникальному идентификатору
     * этого платежа. Создание возврата возможно только для платежей в статусе `succeeded`. Комиссии за проведение
     * возврата нет. Комиссия, которую Яндекс.Касса берёт за проведение исходного платежа, не возвращается.
     *
     * @param CreateRefundRequestInterface $refundsRequest
     * @param null $idempotencyKey
     * @return CreateRefundResponse
     */
    public function createRefund(CreateRefundRequestInterface $refundsRequest, $idempotencyKey = null)
    {
        $path = '/refunds';

        $headers = array();

        if ($idempotencyKey) {
            $headers[self::IDEMPOTENCY_KEY_HEADER] = $idempotencyKey;
        }

        $serializer = new CreateRefundRequestSerializer();
        $serializedData = $serializer->serialize($refundsRequest);
        $httpBody = $this->encodeData($serializedData);
        $response = $this->apiClient->call($path, HttpVerb::POST, null, $httpBody, $headers);

        $result = null;
        if ($response->getCode() == 200) {
            $resultArray = $this->decodeData($response);
            $result = new CreateRefundResponse($resultArray);
        } else {
            $this->handleError($response);
        }
        return $result;
    }

    /**
     * Получить информацию о возврате
     * @param $refundId
     * @return RefundResponse
     */
    public function getRefundInfo($refundId)
    {
        if ($refundId === null) {
            throw new \InvalidArgumentException('Missing the required parameter $refundId');
        }

        $path = '/refunds/' . $refundId;
        $response = $this->apiClient->call($path, HttpVerb::GET, null);

        $result = null;
        if ($response->getCode() == 200) {
            $resultArray = $this->decodeData($response);
            $result = new RefundResponse($resultArray);
        } else {
            $this->handleError($response);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param $serializedData
     * @return string
     * @throws \Exception
     */
    private function encodeData($serializedData)
    {
        $result = json_encode($serializedData);
        if ($result === false) {
            $errorCode = json_last_error();
            throw new JsonException("Failed serialize json.", $errorCode);
        }
        return $result;
    }

    /**
     * @param ResponseObject $response
     * @return array
     */
    private function decodeData(ResponseObject $response)
    {
        $resultArray = json_decode($response->getBody(), true);
        if ($resultArray === null) {
            throw new JsonException('Failed to decode response', json_last_error());
        }
        return $resultArray;
    }

    /**
     * @param ResponseObject $response
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws UnauthorizedException
     * @throws ApiException
     */
    private function handleError(ResponseObject $response)
    {
        switch ($response->getCode()) {
            case BadApiRequestException::HTTP_CODE:
                throw new BadApiRequestException($response->getHeaders(), $response->getBody());
                break;
            case ForbiddenException::HTTP_CODE:
                throw new ForbiddenException($response->getHeaders(), $response->getBody());
                break;
            case UnauthorizedException::HTTP_CODE:
                throw new UnauthorizedException($response->getHeaders(), $response->getBody());
                break;
            case InternalServerError::HTTP_CODE:
                throw new InternalServerError($response->getHeaders(), $response->getBody());
                break;
            case NotFoundException::HTTP_CODE:
                throw new NotFoundException($response->getHeaders(), $response->getBody());
                break;
            case TooManyRequestsException::HTTP_CODE:
                throw new TooManyRequestsException($response->getHeaders(), $response->getBody());
                break;
            default:
                if ($response->getCode() > 399) {
                    throw new ApiException(
                        'Unexpected response error code',
                        $response->getCode(),
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
        }
    }
}