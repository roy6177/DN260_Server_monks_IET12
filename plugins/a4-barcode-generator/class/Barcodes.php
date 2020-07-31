<?php

namespace UkrSolution\WpBarcodesGenerator;

use Melgrati\CodeValidator\CodeValidator;
use UkrSolution\WpBarcodesGenerator\Generators\Generator;

class Barcodes
{
    private $uploadPath = null;
    private $imageUrl = '';
    private $prefixPath = ''; // folder name after uploads folder - uploads/{prefixPath}/2018/08/27/

    /**
     * class constructor with variable initialization.
     * create a folder for barcodes at the current date.
     */
    public function __construct()
    {
        $config = require __DIR__.'/../config/config.php';
        $this->prefixPath = $config['folderBarcode'];
        $uploadDirData = wp_upload_dir();
        $dt = new \DateTime();
        $subfolder = $dt->format('d');
        $this->uploadPath = $uploadDirData['basedir'].'/'.$this->prefixPath.$uploadDirData['subdir'].'/'.$subfolder;
        wp_mkdir_p($this->uploadPath); // create a folder for generated barcodes
        $this->imageUrl = $uploadDirData['baseurl'].'/'.$this->prefixPath.$uploadDirData['subdir'].'/'.$subfolder;
    }

    /**
     * Create barcode file by parameters.
     *
     * @param $data
     *
     * @return string
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function generateFile($data)
    {
        // If it is 2d code, set equal width and height
        if ('QRCODE' === $data['algorithm'] || 'DATAMATRIX' === $data['algorithm']) {
            $w = 12;
            $h = 12;
        } else {
            // Width and height for 1d code
            $w = -8;
            $h = 480;
        }

        // Generate code and save to file.
        $barcodeGenerator = new Generator();
        $barcodeGenerator->setStorPath($this->uploadPath.'/');
        $fileName = $barcodeGenerator->getGeneratedBarcodeSVGFileName($data['field_code'], $data['algorithm'], $w, $h, 'black');

        return $this->imageUrl.'/'.$fileName;
    }

    /**
     * Validation of the barcode.
     *
     * @param string $code
     * @param string $algorithm
     *
     * @return array
     */
    public function validateBarcode($code, $algorithm)
    {
        $validData = array(
            'message' => '',
            'is_valid' => true,
        );
        // if the barcode creation field is empty - output an error
        // separate validation of the field by which the barcode is created
        if (0 == mb_strlen($code)) {
            $validData['is_valid'] = false;
            $validData['message'] = __('The "Code" field has an empty value.', 'wpbcu-barcode-generator');

            return $validData;
        }
        // barcode validation by algorithm
        switch ($algorithm) {
            case 'EAN8': // EAN 8
                $validData['is_valid'] = CodeValidator::IsValidEAN8($code);
                // Put error if code not valid
                if (!$validData['is_valid']) {
                    $validData['message'] = __('"Code" field contains incorrect data. It must contain 8 digits. 7 digits and 8th is a checksum digit calculated by formula. Check more on <a target="_blank" href="https://en.wikipedia.org/wiki/EAN-8">Wiki</a>.', 'wpbcu-barcode-generator');
                }
                break;
            case 'EAN13': // EAN 13
                $validData['is_valid'] = CodeValidator::IsValidEAN13($code);
                // Put error if code not valid
                if (!$validData['is_valid']) {
                    $validData['message'] = __('"Code" field contains incorrect data. It must contain 13 digits. 12 digits and 13th is a checksum digit calculated by formula. Check more on <a target="_blank" href="https://en.wikipedia.org/wiki/International_Article_Number">Wiki</a>.', 'wpbcu-barcode-generator');
                }
                break;
            case 'UPCA': // UPC-A
                $validData['is_valid'] = CodeValidator::IsValidUPCA($code);
                // Put error if code not valid
                if (!$validData['is_valid']) {
                    $validData['message'] = __('"Code" field contains incorrect data. It must contain 12 digits. 11 digits and 12th is a checksum digit calculated by formula. Check more on <a target="_blank" href="https://en.wikipedia.org/wiki/Universal_Product_Code">Wiki</a>.', 'wpbcu-barcode-generator');
                }
                break;
            case 'UPCE': // UPC-E

                // prepare code for validation
                $codeLength = strlen($code);
                // If code contain 7 digit, check if first is meaningless '0'
                if (7 === $codeLength) {
                    // If first digit is '0' we can skip it.
                    if ('0' === $code[0]) {
                        $code = substr($code, 1, 7);
                    }
                }

                $validData['is_valid'] = CodeValidator::IsValidUPCE($code);
                // Put error if code not valid
                if (!$validData['is_valid']) {
                    $validData['message'] = __('"Code" field contains incorrect data. It must contain 6 digits. UPC-E is a variation of UPC-A which allows for a more compact barcode by eliminating "extra" zeros. Check more on <a target="_blank" href="https://en.wikipedia.org/wiki/Universal_Product_Code#UPC-E">Wiki</a>.', 'wpbcu-barcode-generator');
                }
                break;
            case 'C128': // CODE-128 // разрешено много символов и какой-то валидации конкретной не нашел
                $patern = '/^[ -~]+$/';
                $validData['is_valid'] = preg_match($patern, $code);
                // Put error if code not valid
                if (!$validData['is_valid']) {
                    $validData['message'] = __('"Code" field contains incorrect data. Code 128 supports alphanumeric or numeric-only barcodes. It can encode all 128 characters of ASCII encoding.', 'wpbcu-barcode-generator');
                }
                break;
            case 'C39': // CODE-39
                $patern = "#^[0-9a-zA-Z\-\.\ \$\/\+\%]+$#ui";
                // if the line of code does not match the pattern of the barcode algorithm
                if (false == preg_match($patern, $code)) {
                    $validData['is_valid'] = false;
                    $validData['message'] = __('"Code" field contains incorrect data. Code 39 supports 43 characters, consisting of letters (A-Z), numeric digits (0 through 9) and a number of special characters (-, ., $, /, +, %, and space).', 'wpbcu-barcode-generator');
                }
                break;
            case 'QRCODE': // QRCODE
                $validData['is_valid'] = true;
                $validData['message'] = '';
                break;
            case 'DATAMATRIX': // DataMatrix
                $validData['is_valid'] = true;
                $validData['message'] = '';
                break;
        }

        return $validData;
    }
}
