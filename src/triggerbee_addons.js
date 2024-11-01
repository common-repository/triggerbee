// Contact Form 7 Addon
document.addEventListener( 'wpcf7mailsent', function( event )
{
	if( ! window.triggerbee_options )
		return;

	var key = "cf7_" + event.detail.contactFormId;
	
	var mapping = triggerbee_options[key] || {};

	window.mtr_custom = mtr_custom || {};
	window.mtr_custom.session = mtr_custom.session || {};
	
	var consent_gdpr_label = null;
	
	if( event.detail && event.detail.inputs )
	{
		var arrInputs = event.detail.inputs.concat();
		
		for(var i = 0 ; i < arrInputs.length ; i++)
			if( arrInputs[i].name.slice(-2) == "[]" ) 
				arrInputs[i].name = arrInputs[i].name.slice(0, -2);
		
		for(var x = 0 ; x < arrInputs.length ; x++)
		{
			var inputA = arrInputs[x];
			
			for(var y = x + 1 ; y < arrInputs.length ; y++)
			{
				var inputB = arrInputs[y];
				
				if( inputB.name == inputA.name ) 
				{
					inputA.value += "," + inputB.value;
					arrInputs.splice(y, 1);
					y--;
				}
			}
		}
		
    	for ( var i = 0; i < arrInputs.length; i++ )
    	{ 
    		var input = arrInputs[i];  //name:"your-email", value="test"
    		
    		var tbMap = mapping[input.name];
    		
    		if( tbMap ) 
    		{
    			if( tbMap == "__consent_gdpr" )
    				consent_gdpr_label = input.name;
    		
    			else
    				window.mtr_custom.session[tbMap] = input.value;
    		}
        }
    }
	
	var formName = mapping["__FORMNAME"] || "Untitled Form";
	
	mtr.goal( formName ); 
	
	if( consent_gdpr_label && window.mtr_consent ) 
		window.mtr_consent.logConsent(formName, consent_gdpr_label);
}, false );

// Woocommerce Addon
function wooCommerceTracking() {
	if (document.querySelector(".woocommerce")) {
		jQuery(document).on('added_to_cart', function(e) {
			mtr.goal("Added product to cart");
		});
		jQuery(document).on('removed_from_cart', function(e) {
			mtr.goal("Removed product from cart");
		});
		jQuery(document).on('init_checkout', function(e) {
			mtr.goal("Initiated Purchase");
		});
		jQuery(document).on('applied_coupon_in_checkout', function(e) {
			mtr.goal("Applied Coupon in Checkout");
		});
		if (document.querySelector(".woocommerce-thankyou-order-received")) {
			setTimeout(() => {
			var orderRevenue = parseInt(document.querySelector(".woocommerce-Price-amount").innerText);
			if (window.triggerbee.wooCommerceEmailTracking === true){
				var orderEmail = document.querySelector(".woocommerce-customer-details--email").innerText;
				var tbContact = window.mtr_custom || {}; 
				tbContact.session = tbContact.session || {};
				tbContact.session.email = orderEmail; 
				window.mtr_custom = tbContact;
			}
			mtr.goal("Completed Purchase", orderRevenue);
		}, 1000);
		}
	}
}

if (typeof (window.mtr) !== "undefined") {
    wooCommerceTracking();
} else {
   document.addEventListener("afterTriggerbeeReady", function () {
	wooCommerceTracking();
   });
}















