<?php
class EmailConfigModel extends CI_Model
{
	public $facebookLink;
	public $wh;
	public $ins;
	public $telg;
	public $youtube;
	public $baseUrl;
	public $dateTime;
	public $logo;

	function __construct()
	{
		$this->facebookLink	=ConfigData['mail_facebook_link'];
		$this->wh			=ConfigData['mail_whatsapp_link'];
		$this->ins			=ConfigData['mail_instagram_link'];
		$this->telg			=ConfigData['mail_telegram_link'];
		$this->youtube		=ConfigData['mail_youtube_link'];
		$this->baseUrl		=base_url();
		$this->dateTime		=date('l').' '.date('F').' '.date('d').','.date('Y');
		$this->logo			=$this->baseUrl.ConfigData['mail_logo'];
	}

	/**
	 * Email Template Get Configurations
	 */
	function getConfig()
	{
		$getSettingsModel =$this->db->query("SELECT * FROM setting")->row();
		if($getSettingsModel->email_user_name && $getSettingsModel->email_password){

		$config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com', // Gmail SMTP server
            'smtp_port' => 465, // SSL Port for Gmail
            'smtp_user' => 'support@forexcrm.uk', // Your Gmail email address
            'smtp_pass' => 'ymlxsdjofeaamgnr', // Your Gmail password or App Password
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'smtp_crypto' => 'ssl', // Use SSL encryption
        );


		}else{
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' =>ConfigData['default_smtp_host'],
				'smtp_port' => ConfigData['default_smtp_port'],
				'smtp_user' => ConfigData['default_smtp_user'],
				'smtp_pass' => ConfigData['default_smtp_pass'],
				'mailtype'  => 'html',
                'starttls'=>true,
				'charset'   => 'iso-8859-1'
			);
		}


		return $config;
	}

	/**
	 * Email Template From Email Get
	 */
	function getFromEmail(){
		$fromEmail=ConfigData['default_email_from'];
		return $fromEmail;
	}

	/**
	 * Email Template Header part Add
	 */
	function templateHeaderPart($headerMessage=''){

		$facebookLink	=$this->facebookLink;
		$wh				=$this->wh;
		$ins			=$this->ins;
		$telg			=$this->telg;
		$youtube		=$this->youtube;
		$baseUrl		=$this->baseUrl;
		$dateTime		=$this->dateTime;
		$logo			=$this->logo;

		$header='<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <meta name="format-detection" content="telephone=no" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <title>Forget Password</title>
                    <style type="text/css">
						body {
						-webkit-text-size-adjust: 100% !important;
										-ms-text-size-adjust: 100% !important;
										-webkit-font-smoothing: antialiased !important;
									}
									img {
						border: 0 !important;
										outline: none !important;
									}
								   
									table {
						border-collapse: collapse;
										mso-table-lspace: 0px;
										mso-table-rspace: 0px;
									}
									td {
						border-collapse: collapse;
										mso-line-height-rule: exactly;
									}
									a {
						border-collapse: collapse;
										mso-line-height-rule: exactly;
									}
									span {
						border-collapse: collapse;
										mso-line-height-rule: exactly;
									}
									.ExternalClass * {
						line-height: 100%;
									}
									span.MsoHyperlink {
						mso-style-priority: 99;
										color: inherit;
									}
									span.MsoHyperlinkFollowed {
						mso-style-priority: 99;
										color: inherit;
									}
									.em_defaultlink a {
						color: inherit !important;
										text-decoration: none !important;
									}
									.em_white a {
						color: #ffffff;
						text-decoration: none;
									}
									.em_blue1 a {
						color: #2c3e50;
						text-decoration: none;
									}
									.em_blue2 a {
						color: #4777bb;
						text-decoration: none;
									}
									.em_blue3 a {
						color: #2f1d4f;
						text-decoration: none;
									}
									.em_pink a {
						color: #eb47c7;
						text-decoration: none;
									}
									.em_grey a {
						color: #808080;
						text-decoration: none;
									}
									.em_lightblue a {
						color: #4777bb;
						text-decoration: none;
									}
									.em_pink1 a {
						color: #f16a5f;
						text-decoration: none;
									}
									.em_grey1 a {
						color: #666666;
						text-decoration: none;
									}
									.em_pink2 a {
						color: #d436b1;
						text-decoration: none;
									}
							
						@media only screen and (max-width:480px) {
							.em_wrapper {
								width: 100% !important;
							}
											.em_main_table {
								width: 100% !important;
							}
										.em_hide {
							display: none !important;
										}
										.em_full_img {
							width: 100% !important;
							height: auto !important;
										}
										.em_img200 {
							width: 200px !important;
											height: auto !important;
										}
										.em_side {
							width: 10px !important;
										}
										.em_center {
							text-align: center !important;
										}
										.em_center1 {
							text-align: center !important;
											font-size: 18px !important;
											line-height: 20px !important;
										}
										.em_spc_20 {
							height: 20px !important;
										}
										.em_gap_bottom {
							padding-bottom: 20px !important;
										}
										.em_text2 {
							font-size: 14px !important;
											line-height: 16px !important;
										}
										.em_text2_1 {
							font-size: 17px !important;
											line-height: 20px !important;
										}
										.em_text3 {
							font-size: 24px !important;
											line-height: 26px !important;
										}
										.em_text4 {
							font-size: 24px !important;
											line-height: 26px !important;
											text-align: center !important;
										}
										.em_br {
							display: block !important;
										}
										.em_font1 {
							font-size: 18px !important;
											line-height: 20px !important;
										}
										.em_bg {
							background: none !important;
										}
										.em_auto {
							height: auto !important;
										}
									}
							
									@media only screen and (min-width:481px) and (max-width:619px) {
						.em_wrapper {
							width: 100% !important;
						}
										.em_main_table {
							width: 100% !important;
						}
										.em_hide {
							display: none !important;
										}
										.em_full_img {
							width: 100% !important;
							height: auto !important;
										}
										.em_side {
							width: 10px !important;
										}
										.em_center {
							text-align: center !important;
										}
										.em_center1 {
							text-align: center !important;
											font-size: 20px !important;
											line-height: 22px !important;
										}
										.em_spc_20 {
							height: 20px !important;
										}
										.em_gap_bottom {
							padding-bottom: 20px !important;
										}
										.em_br {
							display: block !important;
										}
										.em_font1 {
							font-size: 20px !important;
											line-height: 22px !important;
										}
										.em_bg {
							background: none !important;
										}
										.em_text4 {
							text-align: center !important;
										}
										.em_auto {
							height: auto !important;
										}
									}
                    </style>
                </head>
                <body style="margin:0px; padding:0px;" bgcolor="#ffffff">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
                    <tr>
                        <td valign="top" align="center" bgcolor="#dee5ef"><table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
                            <tr>
                                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="10">&nbsp;</td>
                                        <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                            <tr>
                                                <td height="8" style="line-height:1px; font-size:1px;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                    <tr>
                                                        <td valign="top"><table width="300" border="0" cellspacing="0" cellpadding="0" align="left" class="em_wrapper">
                                                            <tr>
                                                                <td class="em_center" align="left" valign="top" style="font-family: Poppins; font-size:12px; line-height:20px; color:#2c3e50;"><span class="em_blue1">'.ConfigData['site_name'].' '.$headerMessage.'</span></td>
                                                            </tr>
                                                        </table>
                                                            <table width="150" border="0" cellspacing="0" cellpadding="0" align="right" class="em_wrapper">
                                                                <tr>
                                                                    <td class="em_center" align="right" valign="top" style="font-family:Poppins; font-size:12px; line-height:20px; color:#2c3e50;"><span class="em_blue1"><a href="'.ConfigData['mail_site_link'].'" target="_blank" style="text-decoration:underline; color:#2c3e50;" class="inf-track-no">View online</a></span></td>
                                                                </tr>
                                                            </table></td>
                                                    </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                                <td height="10" style="line-height:1px; font-size:1px;">&nbsp;</td>
                                            </tr>
                                        </table></td>
                                        <td width="10">&nbsp;</td>
                                    </tr>
                                </table></td>
                            </tr>
                        </table></td>
                    </tr> 
                    <tr>
                        <td valign="top" align="center"><!--Main header-->
                
                            <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
                                <tr>
                                    <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="10">&nbsp;</td>
                                            <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                <tr>
                                                    <td height="30" class="em_spc_20">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                        <tr>
                                                            <td valign="top"><table width="258" border="0" cellspacing="0" cellpadding="0" align="left" class="em_wrapper">
                                                                <tr>
                                                                    <td align="center" valign="top" class="em_gap_bottom"><a href="#" target="_blank" style="text-decoration:none;" class="inf-track-46582"><img src="'.$logo.'" width="258" height="70" alt="'.ConfigData['site_name'].'" style="display:block; font-family:Poppins; font-size:18px; line-height:23px; color:#000000;" border="0" /></a></td>
                                                                </tr>
                                                            </table>
                                                                <table width="300" border="0" cellspacing="0" cellpadding="0" align="right" class="em_wrapper">
                                                                    <tr>
                                                                        <td align="center" valign="top"><table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
                                                                            <tr>
                                                                                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
                                                                                    <tr>
                                                                                        <td class="em_blue1" align="center" valign="top" style="font-family: Poppins; font-size:30px; line-height:30px; color:#2c3e50;"> STAY CONNECTED</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td height="10" class="em_spc_20">&nbsp;</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="center"><table border="0" cellspacing="0" cellpadding="0" align="center">
                                                                                            <tr>
                                                                                                <td align="right" valign="top"><a href="'.$facebookLink.'" target="_blank" style="text-decoration:none;" class="inf-track-46584"><img src="'.$baseUrl.'assets/images/fb.jpg" width="34" height="34" alt="fb" style="display:block; font-family:Poppins; font-size:15px; line-height:34px; color:#2b3990; text-align:center;" border="0" /></a></td>
                                                                                                <td width="10">&nbsp;</td>
                                                                                                <td align="center" valign="top"><a href="'.$wh.'" target="_blank" style="text-decoration:none;" class="inf-track-46586"><img src="'.$baseUrl.'assets/images/wh.png" width="34" height="34" alt="tweet" style="display:block; font-family:Poppins; font-size:15px; line-height:34px; color:#27aae1; text-align:center;" border="0" /></a></td>
                                                                                                <td width="10">&nbsp;</td>
                                                                                                <td align="center" valign="top"><a href="'.$ins.'" target="_blank" style="text-decoration:none;" class="inf-track-46588"><img src="'.$baseUrl.'assets/images/insta.jpg" width="34" height="34" alt="insta" style="display:block; font-family:Poppins; font-size:15px; line-height:34px; color:#6f90aa; text-align:center;" border="0" /></a></td>
                                                                                                <td width="10">&nbsp;</td>
                                                                                                <td align="center" valign="top"><a href="'.$telg.'" target="_blank" style="text-decoration:none;" class="inf-track-46590"><img src="'.$baseUrl.'assets/images/telegram.png" width="34" height="34" alt="g+" style="display:block; font-family:Poppins; font-size:15px; line-height:34px; color:#e14a35; text-align:center;" border="0" /></a></td>
                                                                                                <td width="10">&nbsp;</td>
                                                                                                <td align="center" valign="top"><a href="'.$youtube.'" target="_blank" style="text-decoration:none;" class="inf-track-46592"><img src="'.$baseUrl.'assets/images/yt.jpg" width="34" height="34" alt="yt" style="display:block; font-family:Poppins; font-size:15px; line-height:34px; color:#ed1c24; text-align:center;" border="0" /></a></td>
                                                                                            </tr>
                                                                                        </table></td>
                                                                                    </tr>
                                                                                </table></td>
                                                                                <td width="5"></td>
                                                                                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                                                    <tr>
                                                                                        <td height="20" style="line-height:1px; font-size:1px;">&nbsp;</td>
                                                                                    </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                </table></td>
                                                        </tr>
                                                        
                                                    </table></td>
                                                </tr>
                                                <tr>
                                                    <td height="20" class="em_spc_20">&nbsp;</td>
                                                </tr>
                                            </table></td>
                                            <td width="10">&nbsp;</td>
                                        </tr>
                                    </table></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td valign="top" align="center"><table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
                            <tr>
                                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="10">&nbsp;</td>
                                        <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                            <tr>
                                                <td class="em_text2 em_center em_blue2" align="left" valign="top" style="font-family:Poppins; font-size:15px; line-height:20px; color:#4777bb; font-weight:bold;"><a href="#" target="_blank" style="text-decoration:none; color:#4777bb;" class="inf-track-46594"></a>'.$dateTime.'</td>
                                            </tr>
                                            <tr>
                                                <td height="20" style="line-height:0px; font-size:0px;"><img src="'.$baseUrl.'assets/images/spacer.gif" width="1" height="1" alt=" " style="display:block;" border="0" /></td>
                                            </tr>
                                        </table></td>
                                        <td width="10">&nbsp;</td>
                                    </tr>
                                </table></td>
                            </tr>
                        </table></td>
                    </tr>
                    <tr><td valign="top" align="center"><table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
                            <tr>
                                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                    <tr>
                                        <td valign="top"><table width="500" border="0" cellspacing="0" cellpadding="0" align="center" class="em_wrapper">
                
                                            <tr>
                                                <td height="20" style="line-height:1px; font-size:1px;">&nbsp;</td>
                                            </tr>
                
                                            <tr>
                                                <td valign="top" align="center" bgcolor="#deecff">
		';

		return $header;
	}

	/**
	 * Email Template Footer part Add
	 */
	function templateFooterPart(){
		$footer='</td>
					</tr>
					<tr>
						 <td valign="top" align="center" style="border: 1px solid gray;"><!--ROI calculator-->
                                    <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
                                        <tr>
                                            <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                        <tr>
                                                            <td align="center" valign="top" width="70%">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                                                    <tr>
                                                                        <td align="center" valign="top">
                                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right" class="em_wrapper" >
                                                                                <tr>
                                                                                    <td height="25" class="em_spc_20">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="em_blue2" align="center" valign="top" style="font-size:16px; line-height:26px; color:#4777bb; font-weight:bold;" colspan="2">
                                                                                       TRADE FROM OUR APP
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="15" class="em_spc_20">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="center" valign="top" class="em_gap_bottom"><a href="#" target="_blank" style="text-decoration:none;" class="inf-track-46582"><img src="'.$this->baseUrl.'assets/images/gplay1.png" width="258" height="70" alt="'.ConfigData['site_name'].'" style="display:block; font-family:Arial, sans-serif; font-size:18px; line-height:23px; color:#000000;" border="0"></a></td>
                                                                                    <td align="center" valign="top" class="em_gap_bottom"><a href="#" target="_blank" style="text-decoration:none;" class="inf-track-46582"><img src="'.$this->baseUrl.'assets/images/app_store.png" width="258" height="70" alt="'.ConfigData['site_name'].'" style="display:block; font-family:Arial, sans-serif; font-size:18px; line-height:23px; color:#000000;" border="0"></a></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="25" class="em_spc_20">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="2" style="line-height:1px; font-size:1px;" class="em_spc_20">&nbsp;</td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                    </table></td>
                                                    <td width="10">&nbsp;</td>
                                                </tr>
                                            </table></td>
                                        </tr>
                                    </table></td>
					</tr>
				</table></td>
			</tr>
			<!--footer-->
			<tr>
				<td valign="top"><table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>

									<tr>
										<td class="em_blue1" align="center" valign="top" style="font-family: Poppins; font-size:14px; line-height:18px; color:#2c3e50;">&copy; 2022 '.ConfigData['site_name'].' Group.<span class="em_br"></span>All Rights Reserved.<br />
											<br />
											<a href="'.ConfigData['mail_site_link'].'" target="_blank" style="text-decoration:underline; color:#2c3e50;margin-right: 10px" class="inf-track-no">Company</a>
											<a href="'.ConfigData['mail_legal_link'].'" target="_blank" style="text-decoration:underline; color:#2c3e50;margin-right: 10px" class="inf-track-no">Legal</a>
											<a href="'.ConfigData['mail_faq_link'].'" target="_blank" style="text-decoration:underline; color:#2c3e50;margin-right: 10px" class="inf-track-no">FAQ</a>
										</td>
									</tr>
									<tr>
										<td height="30" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
				</table></td>
			</tr>
		</table></td>
	</tr>
	</table></td>
		</tr>
	</table>
	<div class="em_hide" style="white-space:nowrap; font:20px courier; color:#ffffff; background-color:#ffffff;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
	</body>
	</html>';

		return $footer;
	}

	/**
	 * During Registrations/ Signup This Email Fire
	 */
	function registrations($userName='',$password='',$fullName=''){

		$templateHeader	=self::templateHeaderPart('registrations mail');
		$templateFooter	=self::templateFooterPart();

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
				<tr>
					<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10">&nbsp;</td>
							<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

								<tr>
									<td height="40" class="em_spc_20">&nbsp;</td>
								</tr>
								<tr>
									<td align="" valign="top">
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Dear '.$fullName.',</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong>Welcome to '.ConfigData['site_name'].'  Markets!</strong> We are thrilled to have you join our community of traders and look forward to helping you achieve your financial goals.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">At '.ConfigData['site_name'].' Markets, we understand the importance of a secure trading platform and offer a range of features to ensure your investments are safe and secure. We also offer a wide selection of instruments and markets to choose from, so you can diversify your portfolio with ease.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">As a new member, you will be able to take advantage of our educational resources to learn more about trading and the markets. Our team of experts is available to answer any questions you may have, and you can access our support team 24/7.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong>In order to start Forex Trading, Please Complete your KYC after Login. Kindly use the following credentials to login. </strong></span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">User ID: '.$userName.'</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Password: '.$password.'</span></span></p>
									
								    <p style="padding-top: 10px;padding-bottom: 10px;">
                                        <a href="https://client.vr19capital.com/login" style="background-color:#4CAF50; color:white; padding:6px 25px; text-align:center; text-decoration:none; display:inline-block; font-size:16px; font-family:Calibri, sans-serif; border-radius:5px;">
                                            Login Here
                                        </a>
                                    </p>
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We are now offering <strong>live trading</strong> accounts on our platform. With a live trading account, you can start trading with real money and take advantage of the most competitive fees and commissions. You will have access to the latest market data and analysis, as well as the ability to manage your portfolio and monitor your positions in real time.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">To get started, simply log in to your existing '.ConfigData['site_name'].' Markets account and select the &ldquo;<strong>Live Trading</strong>&rdquo; option. Once you have completed the account setup process, you will be ready to start trading.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We look forward to helping you become a successful trader and wish you all the best on your trading journey.</span></span></p>
									
									<p>&nbsp;</p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Best regards,</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong>The '.ConfigData['site_name'].' Markets Team</strong></span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"></span></span></p>

									</td>
								</tr>
								  <tr>
									<td height="40" class="em_spc_20">&nbsp;</td>
								</tr>
							</table></td>
							<td width="10">&nbsp;</td>
						</tr>
					</table></td>
				</tr>
			</table>'.$templateFooter.'';

		return $html;

	}

	/**
	 * During Forgot Passsword Its Calling
	 */
	function forgetPassword($uniqueid){

		$templateHeader	=self::templateHeaderPart('forgot password mail');
		$templateFooter	=self::templateFooterPart();

		$resetLink 		=base_url()."reset-password?token=".$uniqueid;

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
										<td align="" valign="top">
										<p>Dear Valued User,</p>
										<p>We hope you are enjoying the experience at <strong>'.ConfigData['site_name'].' Markets</strong>.</p>
										<p>We understand that it can be difficult to remember your password from time to time. If you have forgotten your password, you can reset it by clicking the link below and following the instructions.</p>
										<p>'.$resetLink.'</p>
										<p>Thank you for being a part of the '.ConfigData['site_name'].' Markets family.</p>
										<p>Sincerely,</p>
										<p>The '.ConfigData['site_name'].' Markets Team</p>
										</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
          '.$templateFooter.'';

		return $html;

	}

	function forgetPasswordV2($uniqueid){

		$templateHeader	=self::templateHeaderPart('forgot password mail');
		$templateFooter	=self::templateFooterPart();

		$resetLink 		=base_url()."reset-password-2?token=".$uniqueid.'';

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
										<td align="" valign="top">
										<p>Dear Valued User,</p>
										<p>We hope you are enjoying the experience at <strong>'.ConfigData['site_name'].' Markets</strong>.</p>
										<p>We understand that it can be difficult to remember your password from time to time. If you have forgotten your password, you can reset it by clicking the link below and following the instructions.</p>
										<p>'.$resetLink.'</p>
										<p>Thank you for being a part of the '.ConfigData['site_name'].' Markets family.</p>
										<p>Sincerely,</p>
										<p>The '.ConfigData['site_name'].' Markets Team</p>
										</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
          '.$templateFooter.'';

		return $html;

	}

	/**
	 * Create Trading Account
	 */
	function createTradingAccount($tradeUserName='',$accountNo='',$password="",$groupName="",$leverage="",$investorPassword=""){

		$templateHeader	=self::templateHeaderPart('create trading account');
		$templateFooter	=self::templateFooterPart();

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
										<td align="" valign="top">
										    <p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Dear '.$tradeUserName.'</span></span></p>
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Congratulations! You have successfully created your <strong>Live Trading Account</strong> '.$accountNo.'.</span></span></p>
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We are delighted to have you join us and look forward to helping you make the most of your trading experience. Our platform offers a range of features, including access to real-time market data and advanced charting tools, so you can make informed decisions.</span></span></p>			
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">To get started, please take a few moments to review our user agreement and familiarize yourself with our trading platform. We have also provided a range of resources and educational material to help you become a successful trader.</span></span></p>											
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong>Credentials for your Live Trading Account is</strong></span></span></p>
											
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Account No: '.$accountNo.'</span></span></p>
											
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Password: '.$password.'</span></span></p>
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Investor Password: '.$investorPassword.'</span></span></p>	
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Please do not hesitate to contact us if you have any questions or need further assistance.</span></span></p>
											
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We look forward to helping you reach your financial goals.</span></span></p>
											
											<p>&nbsp;</p>
											
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Sincerely,</span></span></p>
											
											<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">The '.ConfigData['site_name'].' Markets Team</span></span></p>

										</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
          '.$templateFooter.'';

		return $html;

	}

	/**
	 * IB account approval
	 */
	function ibAccountApproval($name='',$userId='',$password=''){

		$templateHeader	=self::templateHeaderPart('ib account approval');
		$templateFooter	=self::templateFooterPart();

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
									
									<td align="" valign="top">
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Dear '.$name.',</span></span></p>

									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We are delighted to inform you that you request has been approved as an <strong>Introducing Broker</strong> of '.ConfigData['site_name'].' Markets.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Welcome to the '.ConfigData['site_name'].' Markets family! We are proud that you have chosen to join us in providing our clients with exceptional services and engaging trading experiences.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">As an Introducing Broker, you will be responsible for introducing clients to our platform and providing them with industry-leading education and support.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We are confident that your experience and expertise will be an invaluable asset to our team. We are looking forward to working with you and helping you achieve success.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong>Please use the following credentials to login as an IB on our Portal. </strong></span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">User ID: '.$userId.'</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Password: '.$password.'</span></span></p>
									
									<p>&nbsp;</p>
									
									<p style="padding-top: 10px;padding-bottom: 10px;">
                                        <a href="https://client.vr19capital.com/login" style="background-color:#4CAF50; color:white; padding:6px 25px; text-align:center; text-decoration:none; display:inline-block; font-size:16px; font-family:Calibri, sans-serif; border-radius:5px;">
                                            Login Here
                                        </a>
                                    </p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Thank you for joining us.</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Sincerely,</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">The '.ConfigData['site_name'].' Markets Team</span></span></p>

										</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
          '.$templateFooter.'';

		return $html;

	}

	/**
	 * Fund Deposit From user panel
	 */
	function fundDeposit($name='',$amount='',$tradingAccount=''){

		$templateHeader	=self::templateHeaderPart('fund deposit');
		$templateFooter	=self::templateFooterPart();

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
									
									<td align="" valign="top">
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Dear '.$name.', </span></span></p>
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We are pleased to inform you that your <strong>fund deposit</strong> to your trading account with '.ConfigData['site_name'].' Marketing has been successful. </span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Your deposit of <strong>$'.$amount.'</strong> to Trading Account<strong> '.$tradingAccount.' </strong> has been credited to your account. </span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We take great pride in ensuring our clients have the best trading experience, and we are excited to provide you with the latest and most advanced trading tools. We look forward to continuing to serve you and help you reach your financial goals. </span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Please do not hesitate to contact us if you have any questions or need further assistance.</span></span></p>
									
									<p>&nbsp;</p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Sincerely,</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">The '.ConfigData['site_name'].' Markets Team</span></span></p>
									
									<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><a href="'.ConfigData['mail_site_link'].'" style="color:#0563c1; text-decoration:underline">'.ConfigData['mail_site_link'].'</a></span></span></p>
									<quillbot-extension-portal></quillbot-extension-portal>
										</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
          '.$templateFooter.'';

		return $html;

	}

	/**
	 * Fund Transfer from One Trading Account to Another
	 */
	function fundTransfer($name='',$amount='',$fromTradingAccount='',$toTradingAccount=''){

		$templateHeader	=self::templateHeaderPart('fund transfer');
		$templateFooter	=self::templateFooterPart();

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
									
									<td align="" valign="top">
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Dear '.$name.',</span></span></p>
			
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We are pleased to inform you that your fund transfer of <strong>$ '.$amount.'</strong> from <strong> '.$fromTradingAccount.' trading account </strong> to <strong> '.$toTradingAccount.' trading account</strong>has been successfully completed. </span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We understand the importance of fund transfers for your trading needs, and we are committed to making it as easy and efficient as possible. We hope this successful transfer provides a positive trading experience for you.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">If you need any assistance with your trading accounts or any other trading related services, please do not hesitate to contact us.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Thank you for choosing '.ConfigData['site_name'].' Marketing for your Forex Trading. </span></span></p>
										
										<p>&nbsp;</p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Sincerely,</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">The '.ConfigData['site_name'].' Markets Team</span></span></p>
									</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
          '.$templateFooter.'';

		return $html;

	}

	/**
	 * Fund Withdrawal Request From user panel
	 */
	function fundWithdraw($name='',$amount='',$fromTradingAccount=''){

		$templateHeader	=self::templateHeaderPart('fund withdraw');
		$templateFooter	=self::templateFooterPart();

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
									
									<td align="" valign="top">
										<p><span style="font-size:11pt"><span style="">Dear '.$name.'</span></span></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We received your request to withdraw funds of amount <strong> $'.$amount.' </strong>from your trading account <strong> '.$fromTradingAccount.' </strong></span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We are writing to confirm that your request is under process and you will receive a confirmation email when we will transfer the requested fund to your Bank/Wallet Account.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We understand that our customers&rsquo; security is of utmost importance and take all requests very seriously. If you have not requested the withdrawal, please contact us as soon as possible so we can take the necessary steps to protect your account from any fraudulent activity.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">If you have any further questions or concerns, please do not hesitate to contact us.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Thank you for being a valued customer of '.ConfigData['site_name'].' Marketing.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Sincerely,</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">The '.ConfigData['site_name'].' Markets Team</span></span></p>
			
									</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
          '.$templateFooter.'';

		return $html;

	}

	/**
	 * Fund Transferred Successfully ->Admin Approve IT
	 */
	function fundTransferApprove($name='',$amount='',$fromTradingAccount=''){

		$templateHeader	=self::templateHeaderPart('fund withdraw');
		$templateFooter	=self::templateFooterPart();

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
									
									<td align="" valign="top">
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Dear '.$name.'</span></span></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We are pleased to inform you that your requested payoutof amount <strong> $'.$amount.' </strong> from trading account <strong> '.$fromTradingAccount.' </strong>has been transferred successfully.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Please note that depending on your bank, it may take a few days for the funds to be reflected in your account.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">We want to thank you for your continued business with '.ConfigData['site_name'].' Marketing. We are dedicated to providing you with the best service possible. Please don&#39;t hesitate to contact us if you have any further questions or concerns.</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Thank you for choosing '.ConfigData['site_name'].' Marketing.</span></span></p>
										
										<p>&nbsp;</p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">Sincerely,</span></span></p>
										
										<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;">The '.ConfigData['site_name'].' Markets Team</span></span></p>
										
										<p>&nbsp;</p>
									</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
          '.$templateFooter.'';

		return $html;

	}

	/**
	 * During Forgot Passsword Its Calling
	 */
	function resetLink($uniqueid,$fullName=''){

		$templateHeader	=self::templateHeaderPart('forgot password mail');
		$templateFooter	=self::templateFooterPart();

		$resetLink 		=base_url()."reset-password?token=".$uniqueid;

		$html=''.$templateHeader.'<table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;" class="em_main_table">
					<tr>
						<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10">&nbsp;</td>
								<td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

									<tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
									<tr>
										<td align="" valign="top">
										<p>Dear '.$fullName.',</p>
                                        <p>As part of our ongoing effort to ensure your safety and security, we are asking all our clients to change their Trade Buddy login passwords.</p>
                                        <p>Recent security threats have made it essential that we take preventive measures to protect your account information. Changing your Trade Buddy login password is the first step in doing so.</p>
                                        <p>We recommend that you choose a password that is at least 8 characters long and a combination of both upper and lower case letters, numbers, and symbols.</p>
                                        <p>If you have any trouble changing your password, our customer service team is available to help you.</p>
                                        <p>We appreciate your cooperation and thank you for being a valued Trade Buddy client.</p>
                                        <p><br></p>
                                        <strong>Click on the link :</strong>
                                      	<p><a href="'.$resetLink.'">'.$resetLink.'</a></p>
                                        <p>Sincerely,</p>
                                        <p><br></p>
                                        <p><br></p>
                                        <p><br></p>
                                        <p>The Trade Buddy Team</p>
										</td>
									</tr>
									  <tr>
										<td height="40" class="em_spc_20">&nbsp;</td>
									</tr>
								</table></td>
								<td width="10">&nbsp;</td>
							</tr>
						</table></td>
					</tr>
          </table>
        ';

		return $html;

	}
}

?>
