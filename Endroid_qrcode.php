<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PHP QR Code porting for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @porting author	4ndr1sw@gmail.com
 * @original author	https://github.com/endroid/qr-code/
 * 
 * @version		1.0
 */

require 'vendor/autoload.php';

use Endroid\QrCode\Bacon\MatrixFactory;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelQuartile;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;

use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeEnlarge;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeInterface;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeShrink;
use Endroid\QrCode\Writer\BinaryWriter;
use Endroid\QrCode\Writer\DebugWriter;
use Endroid\QrCode\Writer\EpsWriter;
use Endroid\QrCode\Writer\PdfWriter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\ValidatingWriterInterface;
use Endroid\QrCode\Writer\WriterInterface;
use Endroid\QrCode\Writer\Result\BinaryResult;
use Endroid\QrCode\Writer\Result\DebugResult;
use Endroid\QrCode\Writer\Result\EpsResult;
use Endroid\QrCode\Writer\Result\PdfResult;
use Endroid\QrCode\Writer\Result\PngResult;
use Endroid\QrCode\Writer\Result\SvgResult;

class Endroid_qrcode 
{

    function generate($params){
            // Create a basic QR code
        $data = isset($params['data']) ? $params['data'] : 'Endroid_qrcode';
        $qrCode = new QrCode($data);

        $setSize = isset($params['setSize']) ? $params['setSize'] : 300;
        $qrCode->setSize($setSize);
        switch ($params['writer']) {
            case 'binary':
                $writer = new BinaryWriter();
                break;
            case 'debug':
                $writer = new DebugWriter();
                break;
            case 'eps':
                $writer = new EpsWriter();
                break;
            case 'png':
                $writer = new PngWriter();
                break;
            default:
                $writer = new SvgWriter();
                break;
        }
        $setMargin = isset($params['setMargin']) ? $params['setMargin'] : 5;
        $qrCode->setMargin($setMargin);

        $setEncoding = isset($params['setEncoding']) ? $params['setEncoding'] : 'UTF-8';
        $qrCode->setEncoding(new Encoding($setEncoding));


        switch ($params['ErrorCorrectionLevel']) {
            case 'hight':
                $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());
                break;
            case 'interface':
                $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelInterface());
                break;
            case 'low':
                $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelLow());
                break;
            case 'quartile':
                $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelQuartile());
                break;            
            default:
                $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelMedium());
                break;
        }

        $qrCode->setRoundBlockSizeMode(new RoundBlockSizeModeMargin());

        $fg_r = isset($params['setForegroundColor']['r']) ? $params['setForegroundColor']['r'] : 0;
        $fg_g = isset($params['setForegroundColor']['g']) ? $params['setForegroundColor']['g'] : 0;
        $fg_b = isset($params['setForegroundColor']['b']) ? $params['setForegroundColor']['b'] : 0;        
        $qrCode->setForegroundColor(new Color($fg_r, $fg_b, $fg_b));

        $bg_r = isset($params['setBackgroundColor']['r']) ? $params['setBackgroundColor']['r'] : 0;
        $bg_g = isset($params['setBackgroundColor']['g']) ? $params['setBackgroundColor']['g'] : 0;
        $bg_b = isset($params['setBackgroundColor']['b']) ? $params['setBackgroundColor']['b'] : 0;        
        $qrCode->setBackgroundColor(new Color($bg_r, $bg_g, $bg_b));

        $logo = null;
        // Create generic logo
        if($params['crateLogo']){
            $logo = Logo::create('./uploads/company/favicon.png')
                ->setResizeToWidth($params['setResizeToWidth']);
        }
        $label = NULL;
        // Create generic label
        if($params['crateLabel']){
            $label = Label::create($params['label'])
                ->setTextColor(new Color(255, 0, 0));            
        }

        $result = $writer->write($qrCode, $logo, $label);

        // Directly output the QR code
        //header('Content-Type: '.$result->getMimeType());
        //echo $result->getString();

        // Save it to a file
        $result->saveToFile($params['saveToFile']);
        //$result->saveToFile(__DIR__.'/qrcode-'.time().'.png');

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();

        return $result;

    }
}
