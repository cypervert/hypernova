jQuery(document).ready(function() {
  // Натягиваем скины на элементы ввода
  // jQuery('.payment_mm_amount, .payment_mm_module').button().addClass('ui-textfield');
  jQuery('.payment_mm_amount, .payment_mm_method:not(.payment_mm_method_nobutton), .payment_mm_module').button().addClass('ui-textfield');

  jQuery(document)
    .on('keyup change', "#metamatter", function(event, ui) {
      value = (value = parseInt(jQuery(this).val())) ? value : 0;
      jQuery(this).val(value);

      current_discount = 1;
      for(i in mm_discounts) {
        if(value >= mm_discounts[i]['sum']) {
          current_discount = mm_discounts[i]['discount'];
        }
      }
      jQuery("#metamatter_total").html(sn_format_number(Math.floor(value * current_discount)));
      if(current_discount > 1) {
        jQuery("#metamatter_undiscounted").show().html(sn_format_number(value));
        jQuery("#metamatter_bonus_percent").show().find('span').html(Math.round(current_discount * 100 - 100));
      } else {
        jQuery("#metamatter_undiscounted").hide();
        jQuery("#metamatter_bonus_percent").hide();
      }

      jQuery("#metamatter_price").html(sn_format_number(Math.ceil(value / exchange_mm_default * 100) / 100, 2));
    })
    .on('focus', "#metamatter", function(event, ui) {
      this.value == '0' ? this.value='' : false;
      this.select();
    })
    .on('blur', "#metamatter", function(event, ui) {
      that = jQuery(this);
      that.val(parseInt(that.val()) ? that.val() : 0);
    })

    .on('change', '#player_currency',function(){
      sn_redirect('metamatter.php?player_currency=' + $(this).val());
    })

    .on("click", ".payment_mm_amount", function(){
      jQuery("#metamatter").val(jQuery(this).attr("value")).change();
      jQuery("#payment_form").submit();
    })
    .on("click", '.payment_mm_method', function(){
      jQuery("#payment_method").val(jQuery(this).attr("value"));
      jQuery("#payment_type").val(jQuery(this).parent().attr("value"));
      jQuery("#payment_form").submit();
    })
    .on("click", '.payment_mm_module', function(){
      jQuery("#payment_module").val(jQuery(this).attr("value"));
      jQuery("#payment_form").submit();
    })
  ;
});
