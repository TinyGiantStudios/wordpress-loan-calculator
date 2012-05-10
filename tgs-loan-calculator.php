<?php
/*
Plugin Name: TGS Loan Calculator
Plugin URI: http://www.tinygiantstudios.co.uk
Description: A sliding loan calculator developed for any wordpress website. This plugin adds a shortcode functionality that can be added to any post or page, along with a multitude of shortcode attributes that can be unique for every instance of the calculator.
Version: 1.0
Author: Tiny Giant Studios
Author URI: http://www.tinygiantstudios.co.uk
License: GPL
*/

/*
TGS Loan Calculator (Wordpress Plugin)
Copyright (C) 2012 Tiny Giant Studios
Contact me at http://www.tinygiantstudios.co.uk
Author: Vian Espost - Tiny Giant Studios 

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/



// =============== [ Include Scripts ] ===============
/* on wp_head load include jquery into the plugin */
function tgs_init_loan_calc() {
	
	// Call jQuery
	wp_enqueue_script('jquery');
	
	// Call jQuery Slider
	wp_register_script("jquery_slider", "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js", array('jquery'), '1.0', false);
	wp_enqueue_script("jquery_slider");
	
	// Call Slider Format Currency
	wp_register_script("jquery_formatcurrency", "/wp-content/plugins/tgs-loan-calculator/js/jquery.formatCurrency-1.4.0.js", array("jquery_slider"), "1.0", false);
	wp_enqueue_script("jquery_formatcurrency");
	
	//Call Slider Currency Region
	wp_enqueue_script("jquery_formatcurrency_regions", "/wp-content/plugins/tgs-loan-calculator/js/jquery.formatCurrency.all.js", array("jquery_formatcurrency"), "1.0", false);
	wp_enqueue_script("jquery_formatcurrency_regions");
	
	// Call Slider stylesheet
	wp_register_style( "ui-stylesheet", "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css", array(), "1.0");
	wp_enqueue_style("ui-stylesheet");
	
	// Call Custom TGS Slider Stylesheet
	wp_register_style( "tgs-custom", "/wp-content/plugins/tgs-loan-calculator/tgs-custom-styles.css", array(), "1.0");
	wp_enqueue_style("tgs-custom");

}
add_action('init', 'tgs_init_loan_calc');

// =============== [ Register Shortcode ] ===============
/* This calls loan_calc_handler function when wordpress recognizes the shortcode in a post.*/
add_shortcode("tgs-loan-calc", "tgs_loan_calc_handler");

/* Handler function */
function tgs_loan_calc_handler($code_arr)
{
    /*[tgs-loan-calc calc_heading="Contact calculator" minimum_loan="150" maximum_loan="90000" 
    default_loan="2000" minimum_term="12" maximum_term="60" default_term="12" initiation_fee_perc="11.4" 
    monthly_life_insurance="100" monthly_service_fee="57" interest_rate="32.1" apply_button_link="index.php"]*/

    
  $code_arr=shortcode_atts(array(
        "calc_heading" => "",
        "minimum_loan" => 150,
        "maximum_loan" => 10000,
        "default_loan" => 2000,
        "minimum_term" => 12,
        "maximum_term" => 60,
        "default_term" => 12,
        "initiation_fee_perc" => 11.4,
        "monthly_life_insurance" => 100,
        "monthly_service_fee" => 57,
        "interest_rate" => 32.1,        
        "apply_button_link" => "#"
      ), $code_arr);
  
    ob_start();

    //run initialise function
    tgs_init_loan_calc();

    //run function that actually does the work of the plugin
    tgs_loan_calc_function($code_arr);

    $output_string=ob_get_contents();;
    ob_end_clean();
  
    //send back text to replace shortcode in post
    return $output_string; 
}


// =============== [ Front End Code to be Generated ] ===============
/* Function that performs work */
function tgs_loan_calc_function($code_arr)
{
    ?>

    <script type="text/javascript">
        $j=jQuery.noConflict();
        
        //DECLARE JS VAR'S
        var minimum_loan = <?php echo $code_arr['minimum_loan'];?>;
        var maximum_loan = <?php echo $code_arr['maximum_loan'];?>;

        var minimum_term = <?php echo $code_arr['minimum_term'];?>;
        var maximum_term = <?php echo $code_arr['maximum_term'];?>;

        var initiation_fee_perc = <?php echo $code_arr['initiation_fee_perc'];?>;

        var slider_loan_amount = 0;
        var sub_total = 0;
        var interest_per_month = 0;
        var total_interest_in_term = 0;
        var loan_period_insurance = 0;
        var loan_period_service = 0;
        var total_fees_and_interest = 0;
        var total_loan_amount = 0;

        var monthly_life_insurance = <?php echo $code_arr['monthly_life_insurance'];?>;
        var monthly_service_fee = <?php echo $code_arr['monthly_service_fee'];?>;
        var interest_rate = <?php echo $code_arr['interest_rate'];?>;
                
        $j(document).ready(function() {            
              
              //Fill min / max value description
              $j("#max_loan_description_amount").val(minimum_loan);
              $j("#max_loan_description_amount").formatCurrency({ colorize:true, region: 'af-ZA' });
              var min_val_text = ($j("#max_loan_description_amount").val());
              $j("#min_loan_description").html("Min "+min_val_text);
              
              $j("#max_loan_description_amount").val(maximum_loan);
              $j("#max_loan_description_amount").formatCurrency({ colorize:true, region: 'af-ZA' });
              var max_val_text = ($j("#max_loan_description_amount").val());
              $j("#max_loan_description").html("Max "+max_val_text);
              
                           
              
              //LOAN AMOUNT SLIDER
              $j("#loan_amount_slider").slider({
                  min: minimum_loan, //minimum value
                  max: maximum_loan, //maximum value
                  value: <?php echo $code_arr['default_loan'];?>, //default value
                  slide: function(event, ui) {
                      slider_loan_amount = ui.value;
                      $j("#loan_amount").val(ui.value);                      
                      $j("#loan_amount").formatCurrency({ colorize:true, region: 'af-ZA' });
                      $j("#loan_amount2").val(ui.value);                      
                      $j("#loan_amount2").formatCurrency({ colorize:true, region: 'af-ZA' });
                      //perform calculation
                      tgs_do_loan_calculations(ui.value,$j("#term_in_months").val());  
                  }
              });
              
              //LOAN SLIDER INCREASE / PLUS BUTTON
              $j('#loan_increase').click(function() {
                  var sliderCurrentValue = $j( "#loan_amount_slider" ).slider( "option", "value" );
                  if(maximum_loan>=(sliderCurrentValue+1)){                  
                      $j( "#loan_amount_slider" ).slider( "value", sliderCurrentValue + 1 );
                      $j("#loan_amount").val(sliderCurrentValue + 1);
                      $j("#loan_amount2").val(sliderCurrentValue + 1);
                      //
                      $j("#loan_amount").formatCurrency({ colorize:true, region: 'af-ZA' });                                        
                      $j("#loan_amount2").formatCurrency({ colorize:true, region: 'af-ZA' });
                      //
                      tgs_do_loan_calculations(sliderCurrentValue + 1,$j("#term_in_months").val());
                  }
              });
               
              //LOAN SLIDER DECREASE / MINUS BUTTON
              $j('#loan_decrease').click(function() {
                  var sliderCurrentValue = $j( "#loan_amount_slider" ).slider( "option", "value" );
                  if(minimum_loan<=(sliderCurrentValue-1)){   
                      $j( "#loan_amount_slider" ).slider( "value", sliderCurrentValue - 1 );
                      $j("#loan_amount").val(sliderCurrentValue - 1);
                      $j("#loan_amount2").val(sliderCurrentValue - 1);
                      //
                      $j("#loan_amount").formatCurrency({ colorize:true, region: 'af-ZA' });                                        
                      $j("#loan_amount2").formatCurrency({ colorize:true, region: 'af-ZA' });
                      //
                      tgs_do_loan_calculations(sliderCurrentValue - 1,$j("#term_in_months").val());
                  }
              });
              
              //TERM LENGTH SLIDER
              $j("#term_slider").slider({
                  min: minimum_term, //minimum value
                  max: maximum_term, //maximum value
                  value: <?php echo $code_arr['default_term'];?>, //default value
                  slide: function(event, ui) {
                      $j("#term_in_months").val(ui.value);  
                      //perform calculation
                      tgs_do_loan_calculations(slider_loan_amount,ui.value);    
                  }
              });
              
              //TERM SLIDER INCREASE / PLUS BUTTON
              $j('#term_increase').click(function() {
                  var sliderCurrentValue = $j( "#term_slider" ).slider( "option", "value" );
                  if(maximum_term>=(sliderCurrentValue+1)){   
                      $j( "#term_slider" ).slider( "value", sliderCurrentValue + 1 );
                      $j("#term_in_months").val(sliderCurrentValue + 1);
                      tgs_do_loan_calculations(slider_loan_amount,sliderCurrentValue + 1);
                  }
              });
              
              //TERM SLIDER DECREASE / MINUS BUTTON
              $j('#term_decrease').click(function() {
                  var sliderCurrentValue = $j( "#term_slider" ).slider( "option", "value" );
                  if(minimum_term <= (sliderCurrentValue-1)){  
                      $j( "#term_slider" ).slider( "value", sliderCurrentValue - 1 );
                      $j("#term_in_months").val(sliderCurrentValue - 1);
                      tgs_do_loan_calculations(slider_loan_amount,sliderCurrentValue - 1);
                  }
              });
              
              //UPDATE LOAN AMOUNT FIELD
              $j("#loan_amount").change(function () {                
                  var new_loan_value = $j("#loan_amount").val();
                  new_loan_value = Number(new_loan_value.replace(/[^0-9\.]+/g,""));
                  
                  if((maximum_loan >= new_loan_value) && (minimum_loan <= new_loan_value)){
                      $j( "#loan_amount_slider" ).slider( "value", new_loan_value );
                      tgs_do_loan_calculations(new_loan_value,$j("#term_in_months").val());
                      tgs_onload_formatting();
                  }
                  else if(new_loan_value > maximum_loan){
                      $j( "#loan_amount_slider" ).slider( "value", maximum_loan );
                      tgs_do_loan_calculations(maximum_loan,$j("#term_in_months").val());
                      tgs_onload_formatting();
                  }
                  else if(new_loan_value < minimum_loan){
                      $j( "#loan_amount_slider" ).slider( "value", minimum_loan );
                      tgs_do_loan_calculations(minimum_loan,$j("#term_in_months").val());
                      tgs_onload_formatting();
                  }
              })
            
              //for formatting of fields
              tgs_onload_formatting();                            
              
              //Fill calculated fields with default values
              tgs_do_loan_calculations(<?php echo $code_arr['default_loan'];?>,<?php echo $code_arr['default_term'];?>);
        }              
        );
        
        //do onload calculations and formatting
        function tgs_onload_formatting(){
              //ON LOAD
              //Populate slider loan amount with minimum value
              $j("#loan_amount").val($j("#loan_amount_slider").slider("value"));
              $j("#loan_amount").formatCurrency({ colorize:true, region: 'af-ZA' });
              
              //Populate slider term length with minimum value
              $j("#term_in_months").val($j("#term_slider").slider("value"));
              
              //Populate calculated fields with loan value
              $j("#loan_amount2").val($j("#loan_amount_slider").slider("value"));
              $j("#loan_amount2").formatCurrency({ colorize:true, region: 'af-ZA' });            
        }
        
        //do calculations
        function tgs_do_loan_calculations(ui_loan_amount,ui_loan_term){
              //DO CALCULATIONS (based on loans and finance excel)                      
              sub_total = (initiation_fee_perc * ui_loan_amount/100) + ui_loan_amount;                      
              loan_period_insurance = ui_loan_term * monthly_life_insurance;
              loan_period_service = ui_loan_term * monthly_service_fee;
              interest_per_month = ((interest_rate/12)*ui_loan_amount)/100;
              total_interest_in_term = interest_per_month*ui_loan_term;
              
              total_fees_and_interest = total_interest_in_term + loan_period_insurance + loan_period_service;
              total_loan_amount = total_fees_and_interest + sub_total;
                                          
              //fill and format fields
              $j("#fees_and_interest").val(total_fees_and_interest);  
              $j("#fees_and_interest").formatCurrency({ colorize:true, region: 'af-ZA' });
              $j("#total_to_repay").val(total_loan_amount);  
              $j("#total_to_repay").formatCurrency({ colorize:true, region: 'af-ZA' });
        }        
        </script>
        
<h3 id="calc_heading"><?php echo $code_arr['calc_heading'];?></h3>
<div id="calc_div">
  <form action="<?php echo $code_arr['apply_button_link'];?>" method="GET" id="slider_form">
    <div class="loan_label">How much cash do you want?</div>
    <input type="button" id="loan_decrease" class="decrease_button" value="-" />
    <div id="loan_amount_slider"></div>
    <input type="button" id="loan_increase" class="increase_button" value="+" />
    <input type="text" name="loan_amount" id="loan_amount" />
    <div id="min_loan_description" class="loan_amount_description"></div>
    <div id="max_loan_description" class="loan_amount_description"></div>
    <input  type="hidden" id="max_loan_description_amount" value="" />
    
    <div class="loan_label">How long do you want it for?</div>
    <input type="button" id="term_decrease" class="decrease_button" value="-" />
    <div id="term_slider"></div>
    <input type="button" id="term_increase" class="increase_button" value="+" />
    <input disabled type="text" id="term_in_months" />
    <div id="months_div">Months</div>
    
  </form>
  
  <div class="clear"></div>
  <form id="calcForm" method="" post="">
    <label class="calcform_label">Borrowing</label>
    <input id="loan_amount2" class="val_input" type="text" value="" />
    
    <label class="calcform_label"> + Interest and fees</label>
    <input id="fees_and_interest" class="val_input" type="text" value="" />
    
    <label class="calcform_label"> = Total to repay</label>
    <input id="total_to_repay" class="val_input" type="text"value="" />
    
    <input type="button" id="apply_now_link" onclick="$j('#slider_form').submit();" value="Apply now" />
    
  </form>
</div>
<?php    
}
?>
