<?php

class Portfolio_SearchEngine {
    private $_ignoredKeywords;
    private $_specialChars;
    private $_minKeywordLength;
    private $_maxKeywords;
    private $_dbConfig;
    
    public function __construct() {
        $this->_ignoredKeywords = array(
            'de',
            'het',
            'een',
            'en',
            'the',
            'of',
            'or'
        );
        
        $this->_specialChars = array(
            '+'  => ' ',
            '_'  => ' ',
            '|'  => ' ',
            '\\' => ' ',
            '/'  => ' ',
            '@'  => '',
            '!'  => '',
            '#'  => '',
            '$'  => '',
            '%'  => '',
            '^'  => '',
            '&'  => '',
            '*'  => '',
            '('  => '',
            ')'  => '',
            '='  => '',
            ','  => '',
            '.'  => '',
            '<'  => '',
            '>'  => '',
            '?'  => '',
            ';'  => '',
            ':'  => '',
            '['  => '',
            ']'  => '',
            '{'  => '',
            '}'  => '',
            '~'  => '',
            '"'  => '',
            '`'  => '',
            "'"  => ''
        );
        
        $this->_minKeywordLength    = 2;
        $this->_maxKeywords         = 10;
        
        $this->_dbConfig = Zend_Registry::get('dbConfig');
    }
    
    /**
     * Set the keywords which will be ignored.
     * @param array $array An array of keywords
     */
    public function setIgnoredKeywords(array $array) {
        $this->_ignoredKeywords = $array;
    }
    
    /**
     * Set the special characters which need to be replaced.
     * @param array $array An array. The keys are the special characters, the value will replace the special characters.
     */
    public function setSpecialChars(array $array) {
        $this->_specialChars = $array;
    }
    
    /**
     * Set the minimum length for a keyword.
     * @param integer $length Integer for the min length of a keyword
     */
    public function setMinKeywordLength(integer $length) {
        $this->_minKeywordLength = $length;
    }
    
    /**
     * Set a maximum for how many keywords will be looked for.
     * @param integer $max The maximum of the keywords which will be looked for.
     */
    public function setMaxKeywords(integer $max) {
        $this->_maxKeywords = $max;
    }

    /**
     * Search in the database for the keywords in the string and order the result.
     * @param string $string A string with keywords which will be looked for.
     * @param string $table The name of the table of the database which need to be searched.
     * @param array $searchColumns An array with the columns which need to be searched. The key is the name of the column and the value is the value of each column (this will multiply the score).
     * @param string $where The where condition. Default is null.
     * @param string $primaryKey The primary key of the table. Default is 'id'.
     * @param array $returnColumns The columns which you want to be returned. Default is null.
     * @return array Returns an ordered array with the found items.
     */
    public function search($string, $table, $searchColumns, $where = null, $primaryKey = 'id', $returnColumns = array()) {
        $keywords = $this->convertStringToKeywords($string);
        
        $data = $this->getData($keywords, $table, $searchColumns, $where, $primaryKey, $returnColumns);
        $data = $this->orderDataByScore($data);
        
        return $data;
    }
    
    /**
     * Format the string to keywords
     * @param string $string A string which will be converted to keyword.
     * @return array Returns an array with keywords.
     */
    private function convertStringToKeywords($string) {
        foreach ($this->_specialChars as $character => $replace) {
            $string = str_replace($character, $replace, $string);
        }
        
        $tmpKeywords = explode(' ', $string);
        $counter = 0;
        $keywords = array();
        foreach ($tmpKeywords as $keyword) {
            if ($counter >= $this->_maxKeywords) {
                break;
            }
            
            if (in_array($keyword, $this->_ignoredKeywords)) {
                continue;
            }
            
            if (strlen($keyword) < $this->_minKeywordLength) {
                continue;
            }
            
            $keywords[] = $keyword;
            $counter++;
        }
        
        return $keywords;
    }
    
    /**
     * Get the data and give each item a score.
     * @param array $keywirds An array filled with keywords which will be looked for.
     * @param string $table The name of the table of the database which need to be searched.
     * @param array $searchColumns An array with the columns which need to be searched. The key is the name of the column and the value is the value of each column (this will multiply the score).
     * @param string $where The where condition.
     * @param string $primaryKey The primary key of the table.
     * @param array $returnColumns The columns which you want to be returned.
     * @return array Returns an array with the found items. Each item has been given a score.
     */
    private function getData($keywords, $table, $searchColumns, $where, $primaryKey, $returnColumns) {
        $db = Zend_Db::factory($this->_dbConfig['adapter'], $this->_dbConfig['params']);
        
        $data = array();
        foreach ($keywords as $keyword) {
            foreach ($searchColumns as $columnName => $multiplier) {
                if ($returnColumns == array()) {
                    if ($where != null) {
                        $result = $db->select()
                            ->from(array($table))
                            ->where("$columnName LIKE '%$keyword%' AND $where")
                            ->query();
                    } else {
                        $result = $db->select()
                            ->from(array($table))
                            ->where("$columnName LIKE '%$keyword%'")
                            ->query();
                    }
                } else {
                    if (!in_array($primaryKey, $returnColumns)) {
                        $returnColumns[] = $primaryKey;
                    }
                    
                    if ($where != null) {
                        $result = $db->select()
                            ->from(array($table), $returnColumns)
                            ->where("$columnName LIKE '%$keyword%' AND $where")
                            ->query();
                    } else {
                        $result = $db->select()
                            ->from(array($table), $returnColumns)
                            ->where("$columnName LIKE '%$keyword%'")
                            ->query();
                    }
                }
                
                foreach ($result as $row) {
                    $key = $row[$primaryKey];
                    if (isset($data[$key])) {
                        $data[$key]['score'] += strlen($keyword) * $multiplier;
                    } else {
                        $data[$key] = array(
                            'item'  => $row,
                            'score' => strlen($keyword) * $multiplier
                        );
                    }
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Order the data by score.
     * @param array $unsortedArray An array filled with data.
     * return array Returns an array ordered on score.
     */
    private function orderDataByScore($unsortedArray) {
        $newData = array();
        
        foreach ($unsortedArray as $newItem) {
            $tmpArray = array();
            if ($newData == array()) {
                $newData[] = $newItem;
            } else {
                $itemSet = false;
                
                foreach ($newData as $item) {
                    if ($newItem['score'] >= $item['score'] && !$itemSet) {
                        $tmpArray[] = $newItem;
                        $itemSet = true;
                    }
                    
                    $tmpArray[] = $item;
                }
                
                if (!$itemSet) {
                    $tmpArray[] = $newItem;
                }
                
                $newData = $tmpArray;
            }
        }
        
        $data = array();
        foreach ($newData as $item) {
            $data[] = (object) $item['item'];
        }
        
        return $data;
    }

}

?>
