/***************** Merge sort function in php **********************/
function merge_sort ($inputArray)
{

    if (count($inputArray) <= 1 )
        return $inputArray;
    
    
    $left  = merge_sort(array_splice($inputArray, 
        floor(count($inputArray) / 2)));
    
    $right = merge_sort($inputArray);
    
    $result = array();
        
    while (count($left) > 0 && count($right) > 0)
    {
        if ($left[0] <= $right[0])
            array_push($result, array_shift($left));
        else
            array_push($result, array_shift($right));
    }
        
    while (count($left) > 0)
        array_push($result, array_shift($left));
  
    while (count($right) > 0)
        array_push($result, array_shift($right));  
        
    return $result;
}

/**Usage**/

$a = array(1,6,3,5,2,7,4,9,8,10);

print_r(merge_sort($a));
