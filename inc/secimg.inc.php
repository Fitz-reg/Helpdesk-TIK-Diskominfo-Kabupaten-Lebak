<?php
/**
 *
 * This file is part of HESK - PHP Help Desk Software.
 *
 * (c) Copyright Klemen Stirn. All rights reserved.
 * https://www.hesk.com
 *
 * For the full copyright and license agreement information visit
 * https://www.hesk.com/eula.php
 *
 */

/* Check if this is a valid include */
if (!defined('IN_SCRIPT')) {die('Invalid attempt');}
#[AllowDynamicProperties]
class PJ_SecurityImage
{

        function __construct($key)
        {
                $this->code = '';
                $this->key = $key;
        } // End PJ_SecurityImage

        function encrypt($plain_text)
        {
            $this->code = trim(sha1($plain_text.$this->key));
        } // End encrypt

        function checkCode($mystring,$checksum)
        {
            $this->encrypt($mystring);
            if ($this->code == $checksum)
                return true;
            else
                return false;
        } // End checkCode

        function printImage($random_number)
        {
            if (!function_exists('imagecreate')) {
                header("Content-Type: image/svg+xml");
                header("Expires: -1");
                header("Cache-Control: no-cache, no-store, must-revalidate, max-age=-1");
                header("Pragma: no-cache");

                $num_str = strval($random_number);
                $svg = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="46" viewBox="0 0 160 46">';
                $svg .= '<rect width="100%" height="100%" fill="#0f172a" rx="8"/>';
                
                // Security noise lines
                $svg .= '<path d="M10 12 Q 80 38 150 12" stroke="#0284c7" stroke-width="2.5" fill="none" opacity="0.7"/>';
                $svg .= '<path d="M10 34 Q 80 8 150 34" stroke="#38bdf8" stroke-width="2" fill="none" opacity="0.7"/>';

                // Render digits
                $colors = ['#38bdf8', '#34d399', '#fbbf24', '#f472b6', '#a78bfa'];
                for ($i = 0; $i < strlen($num_str); $i++) {
                    $char = htmlspecialchars($num_str[$i]);
                    $x = 22 + ($i * 26);
                    $y = 31 + rand(-3, 3);
                    $rot = rand(-12, 12);
                    $color = $colors[$i % count($colors)];
                    $svg .= "<text x=\"{$x}\" y=\"{$y}\" fill=\"{$color}\" font-size=\"24\" font-weight=\"bold\" font-family=\"Courier New, monospace\" transform=\"rotate({$rot}, {$x}, {$y})\">{$char}</text>";
                }
                $svg .= '</svg>';
                echo $svg;
                return;
            }

            $im = @imagecreate(150, 40);
            if (!$im) {
                header("Content-Type: image/svg+xml");
                $num_str = strval($random_number);
                echo '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" width="160" height="46" viewBox="0 0 160 46"><rect width="100%" height="100%" fill="#0f172a" rx="8"/><text x="18" y="32" fill="#38bdf8" font-size="24" font-weight="bold" font-family="monospace">' . htmlspecialchars($num_str) . '</text></svg>';
                return;
            }

            $background_color = imagecolorallocate($im, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));

			for ($i=0;$i<strlen($random_number);$i++)
			{
            	$text_color = imagecolorallocate($im, mt_rand(180,255), mt_rand(180,255), mt_rand(100,255));
				$display = substr($random_number,$i,1);
				$x = ($i*30) + mt_rand(3,16);
				$y = mt_rand(3,26);
				imagestring($im, 5, $x, $y, $display, $text_color);
			}

			if ( function_exists('imagejpeg') )
			{
				header("Content-type: image/jpeg");
				imagejpeg($im);
			}
			elseif ( function_exists('imagepng') )
			{
				header("Content-type: image/png");
				imagepng($im);
			}
			elseif ( function_exists('imagegif') )
			{
				header("Content-type: image/gif");
				imagegif($im);
			}
			else
			{
				header("Content-Type: image/svg+xml");
                $num_str = strval($random_number);
                echo '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" width="160" height="46" viewBox="0 0 160 46"><rect width="100%" height="100%" fill="#0f172a" rx="8"/><text x="18" y="32" fill="#38bdf8" font-size="24" font-weight="bold" font-family="monospace">' . htmlspecialchars($num_str) . '</text></svg>';
                return;
			}

            if (PHP_VERSION_ID < 80500) {
                imagedestroy($im);
            }
        } // End printImage

        function get()
        {
            return $this->code;
        } // End get

} // End class PJ_SecurityImage
