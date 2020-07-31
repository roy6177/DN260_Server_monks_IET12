<?php

namespace UkrSolution\WpBarcodesGenerator\Generators;

use Com\Tecnick\Barcode\Barcode;
use Kreativekorp\Barcode as BarcodeNumbered;

class Generator
{
    protected $storePath;
    protected $barcodeFactory;
    protected $barcodeNumberedFactory;
    protected $barcodeNumberedTypeCodes = array(
        'UPCA' => 'upc-a',
        'UPCE' => 'upc-e',
    );

    /**
     * Generator constructor.
     */
    public function __construct()
    {
        $this->barcodeFactory = new Barcode();
        $this->barcodeNumberedFactory = new BarcodeNumbered();
    }

    /**
     * Generate barcode as SVG, save and get saved file name.
     *
     * @param        $code
     * @param        $type
     * @param int    $w
     * @param int    $h
     * @param string $color
     *
     * @return string
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function getGeneratedBarcodeSVGFileName($code, $type, $w = 2, $h = 30, $color = 'black')
    {
        // If UPCE use old library https://github.com/tecnickcom/TCPDF with fix from https://github.com/milon/barcode/issues/27
        if ('UPCE' === $type) {
            // Code fix. Library doesn't generate correct barcode for 7 and 8 digit variant
            $codeLength = strlen($code);
            // If code contain 7 digit
            if (7 === $codeLength) {
                // If first digit is '0' we can skip it.
                if ('0' === $code[0]) {
                    $code = substr($code, 1, 7);
                } else {
                    // Last digit is check-code, we can skip it.
                    $code = substr($code, 0, 6);
                }
            } elseif (8 === $codeLength) {
                // Remove first '0' and last check-code digit, because library seems doesn't generate correct barcode for this variant.
                if ('0' === $code[0]) {
                    $code = substr($code, 1, 6);
                }
            }

            $tcpdfBarcode = new \TCPDFBarcode($code, 'UPCE');
            $svgContent = $tcpdfBarcode->getBarcodeSVGcode(8, 480, 'black');

//        // Generate barcodes with numbers for upca and upce types.
//        if ('UPCA' === $type || 'UPCE' === $type) {
//
//            // Prepare code before generation barcode
//            if ('UPCE' === $type) {
//                $code = $this->prepareUPCECode($code);
//            }
//
//            $options = array(
//                'p' => 0,
//                'pb' => 6,
//                'th' => 8,
//            );
//
//            $svgContent = $this->barcodeNumberedFactory->render_svg($this->barcodeNumberedTypeCodes[$type], $code, $options);
        }  else {
            // Get SVG code for generated barcode.
            $svgContent = $this->barcodeFactory
                ->getBarcodeObj($type, $code, $w, $h, $color)
                ->setBackgroundColor('white')
                ->getSvgCode();
        }

        // Save content to file.
        $fileName = sha1(md5(uniqid(rand(), 1))).'.svg';
        $saveFile = $this->checkfile($this->storePath.$fileName);
        $result = file_put_contents($saveFile, $svgContent);

        // Return path to file or empty string
        return false !== $result ? $fileName : '';
    }

    /**
     * Set store path for generated files.
     *
     * @param $path
     *
     * @return $this
     */
    public function setStorPath($path)
    {
        $this->storePath = $path;

        return $this;
    }

    /**
     * Check if file exists, remove it.
     *
     * @param $path
     *
     * @return mixed
     */
    protected function checkfile($path)
    {
        // Check if file exists.
        if (file_exists($path)) {
            unlink($path);
        }

        return $path;
    }

    /**
     * Code fix. Library doesn't generate correct barcode for 6 and 7 digit variant.
     *
     * @param $code
     *
     * @return string
     */
    protected function prepareUPCECode($code)
    {
        $codeLength = strlen($code);

        // If code contain 7 digit
        if (7 === $codeLength) {
            // Add * placeholder to make
            $code = '0' === $code[0] ? $code . '*' : '*' . $code;
        } elseif (6 === $codeLength) {
            // Add placeholders
            $code = '*'.$code.'*';
        }

        return $code;
    }
}
