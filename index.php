<!DOCTYPE html>
<html>
<head>
    <title>SDM Solutions</title>
    <link rel="stylesheet" type="text/css" href="styles/pnis_style.css">
</head>
<body>
    <?php
    // Creates an html image tag for the appropriate pokemon
    // Ex. create_image_tag(25) will output <img src='./sprites/025MS.png' title='Pikachu #25'>
    function create_image_tag($poke_number)
    {
        $img_tag_start = "<img ";
        $img_tag_src_start = "src='./sprites/";
        $img_tag_src_middle = sprintf("%'.03d", $poke_number);
        $img_tag_src_end = "MS.png' ";
        $img_tag_title_start = "title='";
        // names.txt holds a list of ordered pokemon names, 1 per line
        $lines = file('names.txt');
        // The ordered list has a newline character we need to get rid of
        // We take off the last character from the string collected to do that
        $img_tag_title_middle_end = substr($lines[$poke_number], 0,-1) . " #" . $poke_number . "'";
        $img_tag_end = ">";
        // Combining the final string
        $img_tag_final = $img_tag_start .
            $img_tag_src_start .
            $img_tag_src_middle .
            $img_tag_src_end .
            $img_tag_title_start .
            $img_tag_title_middle_end .
            $img_tag_end;
        return $img_tag_final;
    }
    // Generate an array of numbers that will represent the provided decimal number using PNIS
    // Lowest array index is the farthest digit to the left
    // Ex. gen_PNIS_number_arr(805) will return [0] => 1, [1] => 2
    // Where [0] is the 803's collumn and [1] is the 1's column
    function gen_PNIS_number_arr($dec_number)
    {
        $base = 803;
        $PNIS_number_arr = [];
        // Trivial case, just return one pokemon with the same number that was provided
        if($dec_number <= $base-1)
        {
            $PNIS_number_arr[] = $dec_number;
            return $PNIS_number_arr;
        }
        else
        {
            // Divide the input by the base (803) and store remainder
            // The quotient becomes the new input number
            // Repeat until the quotient is zero
            // The remainders in reverse order makeup the new number
            $quotient = $dec_number;
            do
            {
                $remainder = bcmod($quotient, $base);
                $quotient = bcdiv($quotient, $base);
                $PNIS_number_arr[] = $remainder;
            }
            while($quotient != 0);
        }
        return array_reverse($PNIS_number_arr);
    }
    function is_input_valid($in)
    {
        $MAX_NUM = "268097813258767128";
        $MIN_NUM = 0;
        // Check arr is filled with the results of many checks
        // Looped through eventually to find if any checks failed
        $check_arr = [];
        // Check its only numbers
        $check_arr[] = is_numeric($in);
        // check its greater than or equal to min number
        $check_arr[] = ($in >= $MIN_NUM);
        // check its less than or equal to max number
        $check_arr[] = (bccomp($MAX_NUM, $in) >= 0);
        for($ii = 0, $arr_size = count($check_arr); $ii < $arr_size; $ii++)
        {
            if(!$check_arr[$ii])
            {
                return FALSE;
            }
        }
        return TRUE;
    }
    ?>
    <form method="GET" class='decimal_convert' action="<?php echo htmlentities( $_SERVER['PHP_SELF'] ); ?>">
        Decimal Number:<br>
        <textarea id="decimal_input" rows="1" cols="20" name="input"><?php echo $_GET['input']?></textarea>
        <input id='decimal_convert_button' type="submit" value="Convert">
    </form>
    <div class="output">
        PNIS Output:<br>
        <div class="pnis_output">
            <?php
            if(isset($_GET['input']) && $_GET['input'] != "")
            {
                if(is_input_valid($_GET['input']))
                {
                    $output_arr = gen_PNIS_number_arr($_GET['input']);
                    for ($ii = 0; $ii < sizeof($output_arr); $ii++)
                    {
                        // Loop through each number in the output array
                        // and create an html image tag for it
                        print(create_image_tag($output_arr[$ii]));
                    }
                }
            }
            ?>
        </div>
    </div>
    
    <!--TODO Add text and images to make site look good-->
    <!--IDEA Create an Input Method Editor (IME) for typing in pnis-->
    <!--IDEA Create a breakdown of how the PNIS representation forms the decimal number
</body>
</html>
