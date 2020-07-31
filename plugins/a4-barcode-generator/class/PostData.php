<?php

namespace UkrSolution\WpBarcodesGenerator;

class PostData
{
    public static $postData = array();

    /**
     * Remember post request data..
     *
     * @param array $data
     */
    public static function set($data = array())
    {
        self::$postData = $data;

        // Remove action field, its no needed, and should not be passed to validation.
        if (isset(self::$postData['action'])) {
            unset(self::$postData['action']);
        }

        // if the parameter 'infinity' is, try to convert it to boolean
        if (isset(self::$postData['infinity'])) {
            self::$postData['infinity'] = ('true' == self::$postData['infinity']) ? true : (('false' == self::$postData['infinity']) ? false : self::$postData['infinity']);
        }

        // if the parameter 'hideCode' is, try to convert it to boolean
        if (isset(self::$postData['hideCode'])) {
            self::$postData['hideCode'] = ('true' == self::$postData['hideCode']) ? true : (('false' == self::$postData['hideCode']) ? false : self::$postData['hideCode']);
        }

        // if the parameter 'hideCode' is, try to convert it to boolean
        if (isset(self::$postData['landscape'])) {
            self::$postData['landscape'] = ('true' == self::$postData['landscape']) ? true : (('false' == self::$postData['landscape']) ? false : self::$postData['landscape']);
        }

        // if the parameter 'withVariations' is, try to convert it to boolean
        if (isset(self::$postData['withVariations'])) {
            self::$postData['withVariations'] = ('true' == self::$postData['withVariations']) ? true : (('false' == self::$postData['withVariations']) ? false : self::$postData['withVariations']);
        }

//        // if the parameter 'code_match' is, try to convert it to boolean
//        if (isset(self::$postData['code_match'])) {
//            self::$postData['code_match'] = ('1' == self::$postData['code_match']) ? true : (('0' == self::$postData['code_match']) ? false : self::$postData['code_match']);
//        }
    }

    public static function get()
    {
        return self::$postData;
    }
}
