<!DOCTYPE html>
<html>
<head>
    <title>SDM Solutions</title>
    <link rel="stylesheet" type="text/css" href="styles/pnis_style.css">
</head>
<body>
    <?php
    // function definitions

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
        if(!is_numeric($in))
        {
            return FALSE;
        }
        if(!($in >= $MIN_NUM))
        {
            return FALSE;
        }
        if(!(bccomp($MAX_NUM, $in) >= 0))
        {
            return FALSE;
        }
        return TRUE;
    }
    function get_random_team_number()
    {
        $BASE = 803;
        $MIN_NUM = 1;
        $MAX_NUM = 802;
        $TEAM_SIZE = 6;
        $team_poke_nums = [];
        for($ii = 0; $ii < $TEAM_SIZE; $ii++)
        {
            $team_poke_nums[] = rand($MIN_NUM, $MAX_NUM);
        }
        // Turn the array into a decimal digit
        $team_decimal_num = 0;
        for($exp = 0; $exp < $TEAM_SIZE; $exp++)
        {
            $team_decimal_num += ($team_poke_nums[$exp] * ($BASE ** $exp));
        }
        return $team_decimal_num;

    }
    function create_PNIS_output_html($decimal_input)
    {
        $output_arr = gen_PNIS_number_arr($decimal_input);
        $PNIS_output_HTML = "";
        for ($ii = 0, $size = count($output_arr); $ii < $size; $ii++)
        {
            // Loop through each number in the output array
            // and create an html image tag for it
            $PNIS_output_HTML .= create_image_tag($output_arr[$ii]);
        }
        return $PNIS_output_HTML;
    }
    ?>
    <?php
    // Start off by finding out if random team was selected
    // If it is we generate a random number for that team and set a random flag
    // these variables will be used by the rest of the following code
    if(isset($_GET['random']) && $_GET['random'] == 1)
    {
        $random_team_number = get_random_team_number();
        $is_random = TRUE;
    }
    else
    {
        $is_random = FALSE;
    }
    ?>
    <form method="GET" class='decimal_convert' action="<?php echo htmlentities( $_SERVER['PHP_SELF'] ); ?>">
        Decimal Number:<br>
        <textarea id="decimal_input" rows="1" cols="20" name="input"><?php
        // Fill in the text area with either the random number generated
        // or with the input supplied
        if($is_random)
        {
            print($random_team_number);
        }
        elseif(isset($_GET['input']))
        {
            print($_GET['input']);
        }
        ?></textarea>
        <input id='decimal_convert_button' type="submit" value="Convert"><br>
        <button id='gen_random_team_button' type="submit" name="random" value="1">Random Team</button>
    </form>
    <div class="output">
        PNIS Output:<br>
        <div class="pnis_output">
            <?php
            if($is_random)
            {
                print(create_PNIS_output_html( $random_team_number ));
            }
            elseif (isset($_GET['input']) && $_GET['input'] != "")
            {
                if(is_input_valid($_GET['input']))
                {
                    print(create_PNIS_output_html( $_GET['input'] ));
                }
                //TODO else{ trigger some error display here }
            }
            ?>
        </div>
    </div>

    <!--TODO Add text and images to make site look good-->
    <!--IDEA Create an Input Method Editor (IME) for typing in pnis-->
    <!--IDEA Create a breakdown of how the PNIS representation forms the decimal number-->
</body>
</html>
