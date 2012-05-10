=== TGS Loan Calculator ===
Author: Tiny Giant Studios
Donate link: http://www.tinygiantstudios.co.uk
Tags: calculator, loan
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: 1.0

== Plugin installation ==

The Loan Calculator plugin allow the website administrator to display a customizable loan calculator on their Wordpress website. 

1. Login as Administrator and install the plugin using the normal wordpress plugin installation screen.
2. Once installed, look for the loan-calculator plugin in the plugin list and activate it.
3. Display the plugin on any page by using the shortcode tag. E.g. [tgs-loan-calc].
4. Go to a post or page, edit it and insert the shortcode tag anywhere in the content of the page/post.
5. Add parameters to the shortcode tag, which will set the various variables used to display and calculate the values on the loan calculator. E.g. Add a minimum loan parameter with a value: [tgs-loan-calc minimum_loan="150"]
6. If a value is left out, the default value will be used. See the list below, along with the default values.

== Usage in a post/page with all possible parameters ==

The values below (i.e. "150") are also the default values. If any of the parameters are left out, the default value will be used.

[						- The shortcode start tag. This is required.
tgs-loan-calc                                   - The shortcode / name of the plugin. This is required.
main_heading="Contact calculator" 		- The main heading which is displayed above the loan amount slider. Not required.
minimum_loan="150" 				- The minimum loan amount that the user can select or type in. Not required.
maximum_loan="10000" 				- The maximum loan amount that the user can select or type in. Not required.
default_loan="2000" 				- When the page is loaded for the first time the loan amount will be set to this value. Not required.
minimum_term="12" 				- The minimum term length the user can select. Not required.
maximum_term="60" 				- The maximum term length the user can select. Not required.
default_term="12" 				- When the page is loaded for the first time the term lenght will be set to this value. Not required.
initiation_fee_perc="11.4" 			- The initiation fee percentage (%) calculated against the loan amount, per year. Not required.
monthly_life_insurance="100" 			- The insurance cost per month, added to the total fees. Not required.
monthly_service_fee="57" 			- The service cost per month, added to the total fees. Not required.
interest_rate="32.1" 				- The interest rate percentage (%), calculated against the loan amount, per year. Not required.
apply_button_link="http://www.google.com"	- The link (URL) for the apply now button. The "http://" at the start of the link is important. Not required.
]						- The shortcode end tag. This is required.

Sample shortcode with default values. This can be used directly in your post:
[tgs-loan-calc calc_heading="Loan calculator" minimum_loan="150" maximum_loan="30000" default_loan="2000" minimum_term="12" maximum_term="60" default_term="12" initiation_fee_perc="11.4" monthly_life_insurance="100" monthly_service_fee="57" interest_rate="32.1" apply_button_link="http://www.google.com"]

For more help regarding the use of the loan Calculator plugin, please email us at info@tinygiantstudios.co.uk.



