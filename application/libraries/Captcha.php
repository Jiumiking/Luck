<?php
/**
 * AUTH  Captcha class / 验证码类
 *
 * @package		AUTH
 * @author		chinkei.chen $2012-5-13
 */
class Captcha
{
	/**
	 * 验证码的session的键值
	 * 
	 * @var string
	 */
	public static $seKey     = 'sid_captcha_ylans_cn';
	
	/**
	 * 验证码过期时间（s）
	 * 
	 * @var int
	 */
	public static $expire    = 300;
	
	/**
	 * 是否使用中文验证码
	 * 
	 * @var bool
	 */
	public static $useZh     = false;
	
	/**
	 * 是否使用使用背景图片
	 * 
	 * @var bool
	 */
	public static $useImgBg  = false;
	
	/**
	 * 验证码字体大小(px)
	 * 
	 * @var int
	 */
	public static $fontSize  = 25;
	
	/**
	 * 是否画混淆曲线
	 * 
	 * @var bool
	 */
	public static $useCurve  = true;
	
	/**
	 * 是否添加杂点
	 * 
	 * @var bool
	 */
	public static $useNoise  = true;
	
	/**
	 * 验证码图片宽
	 * 
	 * @var int
	 */
	public static $imageH    = 0;
	
	/**
	 * 验证码图片长
	 * 
	 * @var int
	 */
	public static $imageL    = 0;
	
	/**
	 * 验证码位数
	 * 
	 * @var int
	 */
	public static $length    = 4;
	
	/**
	 * 背景
	 * 
	 * @var array
	 */
	public static $bg     = array(243, 251, 254);
	
	/**
	 * 验证码中使用的字符，01IO容易混淆，建议不用
	 *
	 * @var string
	 */
	private static $_codeSet   = '346789ABCDEFGHJKLMNPQRTUVWXY';
	
	/**
	 * 验证码图片实例
	 *
	 * @var object
	 */
	private static $_image   = null;
	
	/**
	 * 验证码图片实例
	 *
	 * @var object
	 */
	private static $_color   = null;

	public function __construct( $captcha_cfg ){
		if( !empty($captcha_cfg) ){
			foreach($captcha_cfg as $key=>$value){
				self::$$key = $value;
			}
		}
		//self::$length = 111;
	}
	/**
	 * 验证验证码是否正确
	 *
	 * @param  string $code 用户验证码
	 * @return bool   用户验证码是否正确
	 */
	public static function check($code, $id = '')
	{
		if ( ! isset($_COOKIE)) {
			return false;
		}
			
		// 验证码不能为空
		if(empty($code) || empty($_COOKIE[self::$seKey])) {
			return false;
		}
		$strData = $_COOKIE[self::$seKey];
		$data = json_decode($strData, TRUE);

		$secode = $id ? $data[$id] : $data;
		// session 过期
		if(time() - $secode['time'] > self::$expire) {
			return false;
		}
		if(strtoupper($code) == $secode['code']) {
			return true;
		}

		return false;
	}

	/**
	 * 输出验证码并把验证码的值保存的cookie中
	 * 验证码保存到session的格式为： $_COOKIE[self::$seKey] = array('code' => '验证码值', 'time' => '验证码创建时间');
	 * 
	 * @return void
	 */
	public static function entry($id = '')
	{
		// 图片宽(px)
		self::$imageL || self::$imageL = self::$length * self::$fontSize * 1.5 + self::$fontSize*1.5; 
		// 图片高(px)
		self::$imageH || self::$imageH = self::$fontSize * 2;
		// 建立一幅 self::$imageL x self::$imageH 的图像
		self::$_image = imagecreate(self::$imageL, self::$imageH);
		// 设置背景      
		imagecolorallocate(self::$_image, self::$bg[0], self::$bg[1], self::$bg[2]); 

		// 验证码字体随机颜色
		self::$_color = imagecolorallocate(self::$_image, mt_rand(1,120), mt_rand(1,120), mt_rand(1,120));
		// 验证码使用随机字体
		$ttfPath = dirname(__FILE__) . '/Captcha/' . (self::$useZh ? 'zhttfs' : 'ttfs') . '/';

		$dir = dir($ttfPath);
		$ttfs = array();		
		while (false !== ($file = $dir->read())) {
		    if($file[0] != '.' && substr($file, -4) == '.ttf') {
				$ttfs[] = $ttfPath . $file;
			}
		}
		$dir->close();

		$ttf = $ttfs[array_rand($ttfs)];	
		
		if(self::$useImgBg) {
			self::_background();
		}
		
		if (self::$useNoise) {
			// 绘杂点
			self::_writeNoise();
		} 
		if (self::$useCurve) {
			// 绘干扰线
			self::_writeCurve();
		}
		
		// 绘验证码
		$code = array(); // 验证码
		$codeNX = 0; // 验证码第N个字符的左边距
		for ($i = 0; $i<self::$length; $i++) {
			if(self::$useZh) {
				$code[$i] = chr(mt_rand(0xB0,0xF7)).chr(mt_rand(0xA1,0xFE));
			} else {
				$code[$i] = self::$_codeSet[mt_rand(0, 27)];
				$codeNX += mt_rand(self::$fontSize*1.2, self::$fontSize*1.6);
				// 写一个验证码字符
				self::$useZh || imagettftext(self::$_image, self::$fontSize, mt_rand(-40, 40), $codeNX, self::$fontSize*1.5, self::$_color, $ttf, $code[$i]);
			}
		}
		
		$data = array();
		// 保存验证码
		if($id) {
			$data[$id]['code'] = join('', $code); // 把校验码保存到session\
			$data[$id]['time'] = time();  // 验证码创建时间
		} else {
			$data['code'] = join('', $code); // 把校验码保存到session\
			$data['time'] = time();  // 验证码创建时间
		}
		setcookie(self::$seKey, json_encode($data));

		self::$useZh && imagettftext(self::$_image, self::$fontSize, 0, (self::$imageL - self::$fontSize*self::$length*1.2)/3, self::$fontSize * 1.5, self::$_color, $ttf, iconv("GB2312","UTF-8", join('', $code)));

				
		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);		
		header('Pragma: no-cache');
		header("content-type: image/png");
	
		// 输出图像
		imagepng(self::$_image); 
		imagedestroy(self::$_image);
	}
	
	/** 
	 * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线(你可以改成更帅的曲线函数) 
     *      
     *      高中的数学公式咋都忘了涅，写出来
	 *		正弦型函数解析式：y=Asin(ωx+φ)+b
	 *      各常数值对函数图像的影响：
	 *        A：决定峰值（即纵向拉伸压缩的倍数）
	 *        b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
	 *        φ：决定波形与X轴位置关系或横向移动距离（左加右减）
	 *        ω：决定周期（最小正周期T=2π/∣ω∣）
	 *
	 * @return void
	 */
    protected static function _writeCurve() 
	{
    	$px = $py = 0;
    	
		// 曲线前部分
		$A = mt_rand(1, self::$imageH/2);                  // 振幅
		$b = mt_rand(-self::$imageH/4, self::$imageH/4);   // Y轴方向偏移量
		$f = mt_rand(-self::$imageH/4, self::$imageH/4);   // X轴方向偏移量
		$T = mt_rand(self::$imageH, self::$imageL*2);  // 周期
		$w = (2* M_PI)/$T;
						
		$px1 = 0;  // 曲线横坐标起始位置
		$px2 = mt_rand(self::$imageL/2, self::$imageL * 0.8);  // 曲线横坐标结束位置

		for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {
			if ($w!=0) {
				$py = $A * sin($w*$px + $f)+ $b + self::$imageH/2;  // y = Asin(ωx+φ) + b
				$i = (int) (self::$fontSize/5);
				while ($i > 0) {	
				    imagesetpixel(self::$_image, $px , $py + $i, self::$_color);  // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多				    
				    $i--;
				}
			}
		}
		
		// 曲线后部分
		$A = mt_rand(1, self::$imageH/2);                  // 振幅		
		$f = mt_rand(-self::$imageH/4, self::$imageH/4);   // X轴方向偏移量
		$T = mt_rand(self::$imageH, self::$imageL*2);  // 周期
		$w = (2* M_PI)/$T;		
		$b = $py - $A * sin($w*$px + $f) - self::$imageH/2;
		$px1 = $px2;
		$px2 = self::$imageL;

		for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {
			if ($w!=0) {
				$py = $A * sin($w*$px + $f)+ $b + self::$imageH/2;  // y = Asin(ωx+φ) + b
				$i = (int) (self::$fontSize/5);
				while ($i > 0) {			
				    imagesetpixel(self::$_image, $px, $py + $i, self::$_color);	
				    $i--;
				}
			}
		}
	}
	
	/**
	 * 画杂点
	 * 往图片上写不同颜色的字母或数字
	 * 
	 * @return void
	 */
	protected static function _writeNoise() {
		for($i = 0; $i < 10; $i++){
			//杂点颜色
		    $noiseColor = imagecolorallocate(
		                      self::$_image, 
		                      mt_rand(150,225), 
		                      mt_rand(150,225), 
		                      mt_rand(150,225)
		                  );
			for($j = 0; $j < 5; $j++) {
				// 绘杂点
			    imagestring(
			        self::$_image,
			        5, 
			        mt_rand(-10, self::$imageL), 
			        mt_rand(-10, self::$imageH), 
			        self::$_codeSet[mt_rand(0, 27)], // 杂点文本为随机的字母或数字
			        $noiseColor
			    );
			}
		}
	}
	
	/**
	 * 绘制背景图片
	 * 注：如果验证码输出图片比较大，将占用比较多的系统资源
	 * 
	 * @return void
	 */
	private static function _background() {
		$path = dirname(__FILE__).'/Captcha/bgs/';
		$dir = dir($path);

		$bgs = array();		
		while (false !== ($file = $dir->read())) {
		    if($file[0] != '.' && substr($file, -4) == '.jpg') {
				$bgs[] = $path . $file;
			}
		}
		$dir->close();

		$gb = $bgs[array_rand($bgs)];

		list($width, $height) = @getimagesize($gb);
		// Resample
		$bgImage = @imagecreatefromjpeg($gb);
		@imagecopyresampled(self::$_image, $bgImage, 0, 0, 0, 0, self::$imageL, self::$imageH, $width, $height);
		@imagedestroy($bgImage);
	}
}
