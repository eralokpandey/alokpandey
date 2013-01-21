<?php
        ######################################################################################
        ##     Function to connect with databse                                             ##
        ######################################################################################
        class DB
        {
                var $db_host='localhost';

                var $db_user='root';
        
                var $db_pass='innowar@123';
        
                var $db_name='core_cardealers';
           
                var $dbCon;     
                
                  ######################################################################################
                  ##     Function to connect with databse                                             ##
                  ######################################################################################
                        public function connect() {
        
                            $this->dbCon=mysql_connect($this->db_host, $this->db_user, $this->db_pass); 
            
                            mysql_select_db($this->db_name) or die(mysql_error()); 
        
                        }
                
                
                   ######################################################################################
                   ##     FUNCTION TO REMOVE QUOTES                                                    ##
                   ######################################################################################
                        public function remove_quotes ( $string_to_change )   {
                                $string_to_change = str_replace("'", "&#39;", $string_to_change);
                                $string_to_change = str_replace("\"", "&quot;", $string_to_change);
                        //    $string_to_change=str_replace("\\", "&#92;", $string_to_change);
                        
                                return $string_to_change;
                        }
                  ######################################################################################
                  ##     FUNCTION TO ADD QUOTES
                  ######################################################################################
                        public function add_single_quote($string)  {
                                $string=str_replace("&rsquo;","'",$string);
                                $string=str_replace("&rsquo;","'",$string);
                                return $string;
                        }
                ######################################################################################
                ##     Function to insert data in any table                                         ##
                ######################################################################################
                function insert_data ( $table_name )  
                {
                        $id="";
                        $arr_types="";
                        $arr_types =  array("TRC_","TR_", "TN_", "TREF_", "PHR_", "PHN_", "IR_", "IN_", "MR_", "MN_", "TNEF_", "TRFN_", "TNFN_","TNURL_","TRURL_","FXN_");
                
                        if (!empty($GLOBALS["_POST"])) {
                                reset($GLOBALS["_POST"]);
                                while (list($k,$v)=each($GLOBALS["_POST"]))  {
                                        for($p=0;$p<count($arr_types);$p++)  {
                                                if(stristr($k,$arr_types[$p])!="")  {
                                                        $k = str_replace($arr_types[$p],"",$k);
                                                }
                                        }
                                        ${$k}=$v;
                                }   
                        }
                
                        if (!empty($GLOBALS["_GET"])) {
                                reset($GLOBALS["_GET"]);
                                while (list($k,$v)=each($GLOBALS["_GET"]))  {
                                        for($p=0;$p<count($arr_types);$p++)  {
                                                if(stristr($k,$arr_types[$p])!="")  {
                                                        $k = str_replace($arr_types[$p],"",$k);
                                                }
                                        }
                                        ${$k}=$v;
                                }   
                        }
                   
                        $query = "";
                        $result = mysql_query("SHOW FIELDS FROM $table_name");
                        $query = "INSERT INTO $table_name SET ";
                        while ($a_row = mysql_fetch_array($result, MYSQL_ASSOC))  {
                                $field = "$a_row[Field]";
                                if($field != $id)  {
                                        if(isset($$field))  {
                                                $query .= $field . "=";
                                                $query .= "'" . $this->remove_quotes($$field) . "' , ";
                                        } else {
                                                if(isset($GLOBALS["$field"]))  {
                                                        $query .= $field . "=";
                                                        $query .= "'" . remove_quotes($GLOBALS["$field"]) . "' , ";
                                                }
                                        }
                                }
                        }
                   
                        $query = substr($query, 0, -2);
                        $result = mysql_query($query);
                        return $result;
                }
                
                ######################################################################################
                ##     Function to edit data from any table                                         ##
                ######################################################################################
                function edit_data ( $table_name, $fldname, $fldval )     {
                        $id="";
                        $arr_types="";
                        $arr_types =  array("TRC_","TR_", "TN_", "TREF_", "PHR_", "PHN_", "IR_", "IN_", "MR_", "MN_", "TNEF_", "TRFN_", "TNFN_","TNURL_","TRURL_","FXN_");
                
                        if ( !empty($GLOBALS["_POST"]) )  {
                                reset($GLOBALS["_POST"]);
                                while (list($k,$v) = each($GLOBALS["_POST"]))   {
                                        for ( $p=0; $p<count($arr_types); $p++ )  {
                                                if ( stristr($k, $arr_types[$p]) != "" )  {
                                                        $k = str_replace($arr_types[$p], "", $k);
                                                }
                                        }
                                        ${strtolower($k)} = $v;
                                        //echo "<br> k =$k -- v = $v";
                                }   
                        }
                
                        if (!empty($GLOBALS["_GET"])) {
                                reset($GLOBALS["_GET"]);
                                while (list($k,$v) = each($GLOBALS["_GET"]))  {
                                        for ( $p=0; $p<count($arr_types); $p++ )   {
                                                if ( stristr($k,$arr_types[$p]) != "" )  {
                                                        $k = str_replace($arr_types[$p], "", $k);
                                                }
                                        }
                                        ${strtolower($k)} = $v;
                                }   
                        }
                
                        $query = "";
                        $result = mysql_query("SHOW FIELDS FROM $table_name");
                        $query = "UPDATE $table_name SET ";
                   
                        while ($a_row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                $field = "$a_row[Field]";
                                if($field != $fldname)  {
                                        if(isset($$field)) {
                                                $query .= $field . "=";
                                                $query .= "'" . remove_quotes($$field) . "' , ";
                                        } else {
                                                if(isset($GLOBALS["$field"]))  {
                                                        //echo "<br> var ".$GLOBALS["$field"];
                                                        $query .= $field . "=";
                                                        $query .= "'" . remove_quotes($GLOBALS["$field"]) . "' , ";
                                                }
                                        }
                                }
                        } // while closed here
                   
                        $query = substr($query, 0, -2);
                        $query .= "WHERE $fldname='$fldval'";
                   
                        $result = mysql_query($query);
                        return $result;
                }
                
            ######################################################################################
                ##     Function to edit data from any table  wtih Multiple condition                ##
                ######################################################################################
                function edit_data_with_multiple_cond( $table_name,$where_condition)     {
                        $id="";
                        $arr_types="";
                        $arr_types =  array("TRC_","TR_", "TN_", "TREF_", "PHR_", "PHN_", "IR_", "IN_", "MR_", "MN_", "TNEF_", "TRFN_", "TNFN_","TNURL_","TRURL_","FXN_");
                
                        if ( !empty($GLOBALS["_POST"]) )  {
                                reset($GLOBALS["_POST"]);
                                while (list($k,$v) = each($GLOBALS["_POST"]))   {
                                        for ( $p=0; $p<count($arr_types); $p++ )  {
                                                if ( stristr($k, $arr_types[$p]) != "" )  {
                                                        $k = str_replace($arr_types[$p], "", $k);
                                                }
                                        }
                                        ${strtolower($k)} = $v;
                                        //echo "<br> k =$k -- v = $v";
                                }   
                        }
                
                        if (!empty($GLOBALS["_GET"])) {
                                reset($GLOBALS["_GET"]);
                                while (list($k,$v) = each($GLOBALS["_GET"]))  {
                                        for ( $p=0; $p<count($arr_types); $p++ )   {
                                                if ( stristr($k,$arr_types[$p]) != "" )  {
                                                        $k = str_replace($arr_types[$p], "", $k);
                                                }
                                        }
                                        ${strtolower($k)} = $v;
                                }   
                        }
                
                        $query = "";
                        $result = mysql_query("SHOW FIELDS FROM $table_name");
                        $query = "UPDATE $table_name SET ";
                   
                        while ($a_row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                $field = "$a_row[Field]";
                                if($field != $fldname)  {
                                        if(isset($$field)) {
                                                $query .= $field . "=";
                                                $query .= "'" . remove_quotes($$field) . "' , ";
                                        } else {
                                                if(isset($GLOBALS["$field"]))  {
                                                        //echo "<br> var ".$GLOBALS["$field"];
                                                        $query .= $field . "=";
                                                        $query .= "'" . remove_quotes($GLOBALS["$field"]) . "' , ";
                                                }
                                        }
                                }
                        } // while closed here
                   
                        $query = substr($query, 0, -2);
                        $query .= " $where_condition";
                   
                        $result = mysql_query($query);
                        return $result;
                }
                ######################################################################################
                ##     Function to get one value                                                    ##
                ######################################################################################
                
                function get_one_value ( $table_name, $field_name, $key=1, $val=1 )  {   
                        $sql = "SELECT $field_name FROM $table_name WHERE $key='$val' ";   
                        $qry = mysql_query($sql) or die("Error: Function -> get_one_value(): ".mysql_error());
                        $res = mysql_fetch_assoc($qry);
                   
                        $result = ucwords(strtolower($res[$field_name]));
                        return $result;
                }
        
}
?>
