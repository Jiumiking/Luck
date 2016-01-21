<?php
/**
 * Message 发送短信
 * Jiumi 2015/11/23
 */
class Message{
    private $sdk;
    private $code_md5;
    private $subcode;
    private $verfy_before;
    private $verfy_after;
    private $url;

    public function __construct(){
        $this->sdk = '18051957082';
        $this->code_md5 = '8678770e21e4e7c8901c1f7ac6398f9b';
        $this->subcode = '2697';
        $this->company_name = '【樱淘管家】';

        $this->url = 'http://sdk.4001185185.com/sdk/smssdk!mt.action';
    }
    /**
     * 会员卡绑定
     *
     * @access  public
     * @return  void
     */
    public function message_card_binding( $moblie, $card_no ){
        if( empty($moblie) || empty($card_no) ){
            return '参数错误';
        }
        $content = "亲爱的会员，您的会员卡绑定成功，卡号：".$card_no."。";
        return $this->message_send( $moblie, $content );
    }
    /**
     * 会员卡消费
     *
     * @access  public
     * @return  void
     */
    public function message_card_less( $moblie, $less, $balance ){
        if( empty($moblie) || empty($less) || empty($balance) ){
            return '参数错误';
        }
        $content = "亲爱的会员，本次消费金额：".$less."，会员卡余额：".$balance."。";
        return $this->message_send( $moblie, $content );
    }
    /**
     * 会员卡充值
     *
     * @access  public
     * @return  void
     */
    public function message_card_add( $moblie, $add, $balance ){
        if( empty($moblie) || empty($add) || empty($balance) ){
            return '参数错误';
        }
        $content = "亲爱的会员，本次充值金额：".$add."，会员卡余额：".$balance."。";
        return $this->message_send( $moblie, $content );
    }
    /**
     * 发送
     *
     * @access  private
     * @return  void
     */
    private function message_send( $mobile, $content ){
        $url = $this->url.'?sdk='.$this->sdk.'&code='.$this->code_md5.'&pwdtype=md5&phones='.$mobile.'&msg='.$content.$this->company_name.'&subcode='.$this->subcode;
        return $this->request($url);
    }
    /**
     * curl
     *
     * @access  private
     * @return  void
     */
    private function request( $url ){
        $ch = curl_init();
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
}


