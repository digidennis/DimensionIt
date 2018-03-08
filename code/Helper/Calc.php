<?php


class Digidennis_DimensionIt_Helper_Calc extends Mage_Core_Helper_Abstract
{
    const PATTERN = '/(?:\-?\d+(?:\.?\d+)?[\+\-\*\/])+\-?\d+(?:\.?\d+)?/';
    const PARENTHESIS_DEPTH = 10;
    public function calculate($input){
        if(strpos($input, '+') != null || strpos($input, '-') != null || strpos($input, '/') != null || strpos($input, '*') != null){
            //  Remove white spaces and invalid math chars
            $input = str_replace(',', '.', $input);
            $input = preg_replace('[^0-9\.\+\-\*\/\(\)]', '', $input);
            //  Calculate each of the parenthesis from the top
            $i = 0;
            while(strpos($input, '(') || strpos($input, ')')){
                $input = preg_replace_callback('/\(([^\(\)]+)\)/', 'self::callback', $input);
                $i++;
                if($i > self::PARENTHESIS_DEPTH){
                    break;
                }
            }
            //  Calculate the result
            if(preg_match(self::PATTERN, $input, $match)){
                return $this->compute($match[0]);
            }
            return 0;
        }
        return $input;
    }
    public function processFormular($posteddimensions, $formular )
    {
        $matches = null;
        $solvedformular = $formular;

        $test = preg_match_all('/{+(.*?)}/', $formular, $matches, PREG_SET_ORDER);
        if($test)
        {
            foreach ($matches as $match)
            {
                if( key_exists( $match[1], $posteddimensions))
                    $solvedformular = str_replace($match[0],$posteddimensions[$match[1]]['value'],$solvedformular);
            }
        }

        return $solvedformular;
    }
    private function compute($input){
        $compute = create_function('', 'return '.$input.';');
        return 0 + $compute();
    }
    private function callback($input){
        if(is_numeric($input[1])){
            return $input[1];
        }
        elseif(preg_match(self::PATTERN, $input[1], $match)){
            return $this->compute($match[0]);
        }
        return 0;
    }
}