<?php
namespace App\Libraries;

/**
 * Class MahJsonAPI
 * EGHL Terminal/OGL JSON API client (JWT RS256 signed).
 * @version 0.0.4
 * @copyright © 2022 GHL SYSTEMS BERHAD
 */
class MahJsonAPI
{
    //reference message
    public $security = 0;
    public $version = 1;
    protected $MsgType = "";
    protected $TxnType = "";
    public $Amount  = "";
    public $MerchantID = "";
    public $OperatorID = "";
    protected $RetTxnRef = "";
    public $TerminalID = "";
    public $ProductCode ="";
    public $AccountNo = "";
    protected $PosDateTime = "";
    public $TxnTraceID;
    public $CustomField2;

    protected $url = 'https://ws.ghlapps.com/oglws/json';
    private $privateKey;
    private $publicKey;

    public function __construct($pubkeyPath, $prikeyPath)
    {
        $this->publicKey = file_get_contents($pubkeyPath);
        $this->privateKey = file_get_contents($prikeyPath);
    }

    public function setMsgType($msgType)
    {
        $this->MsgType = $msgType;
    }

    public function setTxnType($txnType)
    {
        $this->TxnType = $txnType;
    }

    //mistake-proofing
    protected function unsetAccountNo()
    {
        $this->AccountNo = '';
    }

    protected function unsetProductCode()
    {
        $this->ProductCode = '';
    }

    public function setNewRetTxnRef($RetTxnRef)
    {
        $this->RetTxnRef = md5($RetTxnRef);
    }
    public function setLatRetTxnRef($RetTxnRef)
    {
        $this->RetTxnRef = $RetTxnRef;
    }
    protected function setRetTxnRef()
    {
        $this->RetTxnRef = md5(uniqid(mt_rand(), true));
    }

    protected function setPosDateTime()
    {
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $this->PosDateTime = date("YmdHis");
    }
    public function setNewPosDateTime($time)
    {
        $this->PosDateTime = $time;
    }

    protected function getPayload()
    {
        return $this->security .
            $this->version .
            $this->MsgType .
            $this->TxnType .
            $this->TxnTraceID .
            $this->AccountNo .
            $this->ProductCode .
            $this->Amount .
            $this->MerchantID .
            $this->TerminalID .
            $this->RetTxnRef .
            $this->PosDateTime;
    }

    public function postJson($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            throw new \RuntimeException("EGHL cURL error [{$errno}]: {$error}");
        }
        curl_close($ch);

        return $result;
    }

    public function sign($data)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
        $headerEncode = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $payloadEncode = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        $result = openssl_sign($headerEncode . '.' . $payloadEncode, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        if (!$result) {
            throw new \RuntimeException('EGHL signing failed: ' . openssl_error_string());
        }
        $signEncode = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $headerEncode . "." . $payloadEncode . "." . $signEncode;

        return $jwt;
    }

    /**
     * Verifies EGHL's JWT-signed response.
     * @return string the original $response if signature is valid, or the literal string "Invalid signature" if not.
     * Callers MUST check for that literal string before trusting the response.
     */
    public function verify($response)
    {
        // extract payload from server response
        $string_response = preg_replace('/:\s*(\-?\d+(\.\d+)?([e|E][\-|\+]\d+)?)/', ': "$1"', $response);
        $msg = json_decode($string_response, true);
        if (!is_array($msg) or array_keys($msg) != ['security', 'version', 'msg', 'signature']) {
            return $response;
        }
        $payloadRef = ['security', 'version', 'MsgType', 'TxnType', 'TxnTraceID', 'AccountNo', 'ProductCode', 'Amount', 'MerchantID', 'TerminalID', 'RetTxnRef', 'POSDateTime', 'TxnDateTime', 'TxnRef', 'ResponseCode', 'ResponseMsg'];
        $payload = join('', array_slice($msg, 0, 2));
        foreach ($payloadRef as $item) {
            if (array_key_exists($item, $msg['msg'])) {
                $payload .= $msg['msg'][$item];
            }
        }
        // construct jwt
        $header = $msg['signature']['parameter'];
        $headerEncode = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $payloadEncode = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
        $signDecode = base64_decode(str_pad(strtr($msg['signature']['value'], '-_', '+/'), strlen($msg['signature']['value']) % 4, '=', STR_PAD_RIGHT));
        $toSign = $headerEncode . "." . $payloadEncode;
        $result = openssl_verify($toSign, $signDecode, $this->publicKey, OPENSSL_ALGO_SHA256);

        return $result == 1 ? $response : "Invalid signature";
    }

    /**
     * Asynchronous Payment (Sale) — used to generate the DuitNow QR code.
     * @return string
     */
    public function paymentAsynchronous()
    {
        $this->setMsgType("Sale");
        $this->setTxnType("PMT");
        $this->setPosDateTime();
        $jws = $this->sign($this->getPayload());
        $sign = str_getcsv($jws, '.');
        $json = array(
            "security" => $this->security,
            "version" => $this->version,
            "msg" => [
                "MsgType" => $this->MsgType,
                "TxnType" => $this->TxnType,
                "Amount" => $this->Amount,
                "MerchantID" => $this->MerchantID,
                "OperatorID" => $this->OperatorID,
                "RetTxnRef" => $this->RetTxnRef,
                "TerminalID" => $this->TerminalID,
                "ProductCode" => $this->ProductCode,
                "AccountNo" => $this->AccountNo,
                "POSDateTime" => $this->PosDateTime,
                "TxnTraceID" => $this->TxnTraceID
            ],
            "signature" => [
                "type" => "JWT",
                "value" => $sign[2],
                "parameter" => "{\"typ\":\"JWT\",\"alg\":\"RS256\"}"
            ]
        );
        $response = $this->postJson($this->url, $json);
        return $this->verify($response);
    }

    /**
     * Query — polls the status of a previously created Sale transaction.
     * @param string $orgTxnRef EGHL's TxnRef from the original Sale response.
     * @return string
     */
    public function paymentQuery($orgTxnRef)
    {
        $this->setMsgType("Query");
        $this->setTxnType("PMT");
        $this->setPosDateTime();
        $this->unsetAccountNo();
        $jws = $this->sign($this->getPayload());
        $sign = str_getcsv($jws, '.');
        $json = array(
            "security" => $this->security,
            "version" => $this->version,
            "msg" => [
                "MsgType" => $this->MsgType,
                "TxnType" => $this->TxnType,
                "Amount" => $this->Amount,
                "MerchantID" => $this->MerchantID,
                "OperatorID" => $this->OperatorID,
                "RetTxnRef" => $this->RetTxnRef,
                "OrgTxnRef" => $orgTxnRef,
                "TerminalID" => $this->TerminalID,
                "ProductCode" => $this->ProductCode,
                "POSDateTime" => $this->PosDateTime,
                "TxnTraceID" => $this->TxnTraceID
            ],
            "signature" => [
                "type" => "JWT",
                "value" => $sign[2],
                "parameter" => "{\"typ\":\"JWT\",\"alg\":\"RS256\"}"
            ]
        );
        $response = $this->postJson($this->url, $json);
        return $this->verify($response);
    }
}
