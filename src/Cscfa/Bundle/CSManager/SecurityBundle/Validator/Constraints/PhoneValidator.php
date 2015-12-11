<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Constraint
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\SecurityBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * PhoneValidator.
 *
 * The PhoneValidator is used
 * to validate the phone 
 * constraint.
 *
 * @category Constraint
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PhoneValidator extends ConstraintValidator
{
    /**
     * Is international
     * 
     * Check if the starter is
     * double zero or positive sign
     * 
     * @param string  $phoneString - the phone number
     * 
     * @return boolean
     */
    public function isInternational($phoneString){
        
        if (preg_match("/^(0{2}|\+)/", $phoneString)) {
            return true;
        }else{
            return false;
        }
        
    }
    
    /**
     * Get country code length
     * 
     * Return the country code
     * length. Note that a zero
     * result indicate an unavailable
     * country code, so, an unavailable
     * international phone number.
     * 
     * @param string  $phoneString - the phone number
     * @param integer $sl          - the phone start lenght
     * 
     * @return number
     */
    public function getCountryCodeLength($phoneString, $sl){
        
        $countryCodes = array(
            "1",    "7",    "20",   "27",   "30",   "31",
            "32",   "33",   "34",   "36",   "39",   "40",
            "41",   "43",   "44",   "45",   "46",   "47",
            "48",   "49",   "51",   "52",   "53",   "54",
            "55",   "56",   "57",   "58",   "60",   "61",
            "62",   "63",   "64",   "65",   "66",   "81",
            "82",   "84",   "86",   "90",   "91",   "92",
            "93",   "94",   "95",   "98",   "211",  "212",
            "213",  "216",  "218",  "220",  "221",  "222",
            "223",  "224",  "225",  "226",  "227",  "228",
            "229",  "230",  "231",  "232",  "233",  "234",
            "235",  "236",  "237",  "238",  "239",  "240",
            "241",  "242",  "243",  "244",  "245",  "246",
            "247",  "248",  "249",  "250",  "251",  "252",
            "253",  "254",  "255",  "256",  "257",  "258",
            "260",  "261",  "262",  "263",  "264",  "265",
            "266",  "267",  "268",  "269",  "290",  "291",
            "297",  "298",  "299",  "350",  "351",  "352",
            "353",  "354",  "355",  "356",  "357",  "358",
            "359",  "370",  "371",  "372",  "373",  "374",
            "375",  "376",  "377",  "380",  "381",  "382",
            "385",  "386",  "387",  "389",  "420",  "421",
            "423",  "500",  "501",  "502",  "503",  "504",
            "505",  "506",  "507",  "508",  "509",  "590",
            "591",  "592",  "593",  "594",  "595",  "596",
            "597",  "598",  "599",  "670",  "672",  "673",
            "674",  "675",  "676",  "677",  "678",  "679",
            "680",  "681",  "682",  "683",  "685",  "686",
            "687",  "688",  "689",  "690",  "691",  "692",
            "850",  "852",  "853",  "855",  "856",  "880",
            "886",  "960",  "961",  "962",  "963",  "964",
            "965",  "966",  "967",  "968",  "970",  "971",
            "972",  "973",  "974",  "975",  "976",  "977",
            "992",  "993",  "994",  "995",  "996",  "998"
        );
        
        $ccPattern = "";
        foreach ($countryCodes as $key=>$cc) {
            if($key > 0){
                $ccPattern .= '|';
            }
            
            $ccPattern .= $cc;
        }
        
        $phoneString = substr($phoneString, $sl);
        
        $matching = array();
        if (preg_match("/^(".$ccPattern.")/", $phoneString, $matching)) {
            return strlen($matching[1]);
        }else{
            return 0;
        }
    }
    
    /**
     * Get start length
     * 
     * Return the phone number
     * starter length
     * 
     * @param string  $phoneString - the phone number
     * 
     * @return number
     */
    public function getStartLength($phoneString){

        $matching = array();
        if (preg_match("/^(0{2}|\+)/", $phoneString, $matching)) {
            return strlen($matching[1]);
        } else if ($phoneString[0] == "0") {
            return 1;
        } else {
            return 0;
        }
        
    }
    
    /**
     * Is analysable
     * 
     * Check the E.164 length
     * to be analysed by the
     * telecommunication server
     * 
     * @param string  $phoneString - the phone number
     * @param integer $ccl         - the country code length
     * @param integer $sl          - the phone start lenght
     * 
     * @return boolean
     */
    public function isAnalysable($phoneString, $ccl, $sl){
        
        $phoneString = substr($phoneString, ($sl + $ccl));
        
        return strlen($phoneString) > 4;
        
    }
    
    /**
     * Max overflow valid
     * 
     * Check if the phone number length
     * do not be over 15 number
     * 
     * @param string  $phoneString - the phone number
     * @param integer $sl          - the phone start lenght
     * 
     * @return boolean
     */
    public function maxOverflowValid($phoneString, $sl){
        return (strlen($phoneString) - $sl) <= 15;
    }
    
    /**
     * Is number
     * 
     * Check if the phone number 
     * is a numeric value
     * 
     * @param string  $phoneString - the phone number
     * @param integer $sl          - the phone start lenght
     * 
     * @return boolean
     */
    public function isNumber($phoneString, $sl){
        return is_numeric(substr($phoneString, $sl));
    }
    
    /**
     * validate
     * 
     * This method validate
     * a phone number value.
     * 
     * @param mixed      $value      - the value to validate
     * @param Constraint $constraint - the phone constraint
     * 
     * @see \Symfony\Component\Validator\ConstraintValidatorInterface::validate()
     */
    public function validate($value, Constraint $constraint)
    {
        $sl = $this->getStartLength($value);
        
        if (!$this->isNumber($value, $sl) || !$this->maxOverflowValid($value, $sl) || $sl == 0 ) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
        
        if ($this->isInternational($value)) {
            
            $ccl = $this->getCountryCodeLength($value, $sl);
            
            if ($ccl == 0) {
                $this->context->buildViolation($constraint->message)->addViolation();
            } else if (!$this->isAnalysable($value, $ccl, $sl)) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
            
        }
        
    }
}
