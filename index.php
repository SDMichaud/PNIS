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
        // The ordered list has a space and a newline character we need to get rid of
        // We take off the last two characters from the string collected to do that
        $img_tag_title_middle_end = substr($lines[$poke_number], 0,-2) . " #" . $poke_number . "'";
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
    // Ex. gen_PNIS_number_arr(804) will return [0] => 1, [1] => 2
    // Where [0] is the 802's collumn and [1] is the 1's column
    function gen_PNIS_number_arr($dec_number)
    {
        $number_of_pokemon = 802;
        $PNIS_number_arr = [];
        // Trival case, only 1 pokemon needed
        if($dec_number <= $number_of_pokemon-1)
        {
            $PNIS_number_arr[] = $dec_number;
            return $PNIS_number_arr;
        }
        else
        {
            // Divide the input by 802 and store remainder
            // The quotient becomes the new input number
            // Repeat until the quotient is zero
            // The remainders in reverse order makeup the new number
            $quotient = $dec_number;
            do {
                $remainder = $quotient % $number_of_pokemon;
                $quotient = round($quotient / $number_of_pokemon);
                $PNIS_number_arr[] = $remainder;
            } while ($quotient != 0);
        }
        return array_reverse($PNIS_number_arr);
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
                $output_arr = gen_PNIS_number_arr($_GET['input']);
                for ($ii = 0; $ii < sizeof($output_arr); $ii++)
                {
                    // Loop through each number in the output array
                    // and create an html image tag for it
                    print(create_image_tag($output_arr[$ii]));
                }
            }
            ?>
        </div>
    </div>

    <!--TODO Make input/output blocks-->
    <!--TODO Add forms for converting decimal to pnis-->
    <!--Froms will submit to self where php will be used to process input-->
    <!--TODO Add php script for converting decimal to pnis-->
    <!--TODO Add text and images to make site look good-->
    <!--IDEA Create an Input Method Editor (IME) for typing in pnis-->
</body>
</html>
