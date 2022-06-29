<script>
  var url = 'https://wati-integration-service.clare.ai/ShopifyWidget/shopifyWidget.js?62697';
  var s = document.createElement('script');
  s.type = 'text/javascript';
  s.async = true;
  s.src = url;
  var options = {
      "enabled":true,
      "chatButtonSetting":{
          "backgroundColor":"#4dc247",
          "ctaText":"Chat Us",
          "borderRadius":"25",
          "marginLeft":"20",
          "marginBottom":"40",
          "marginRight":"20",
          "position":"right"
      },
      "brandSetting":{
          "brandName":"Admin",
          "brandSubTitle":"Mediez Health",
          "brandImg":"https://cdn.clare.ai/wati/images/WATI_logo_square_2.png",
          "welcomeText":"Hi there!\nHow can I help you?",
          "messageText":"Hello, I have a question",
          "backgroundColor":"#0a5f54",
          "ctaText":"Start Chat",
          "borderRadius":"25",
          "autoShow":false,
          "phoneNumber":"{{ $whatsapp_number }}"
      }
    };
  s.onload = function() {
      CreateWhatsappChatWidget(options);
  };
  var x = document.getElementsByTagName('script')[0];
  x.parentNode.insertBefore(s, x);
</script>