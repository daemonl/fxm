var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-25844617-1']);
		_gaq.push(['_setDomainName', 'rebase.com.au']);
		_gaq.push(['_trackPageview']);
	  
		(function() {
		  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	  
	  $(document).ready(function(){
		  FadeG();
		  });
		  
		  var fCC = 0;
		  var fLC = 3;
		  var fTimer;
		  function FadeR()
		  {
			  $("#headerLogoR").css("z-index", 5);
			  $("#headerLogoG").css("z-index", 2);
			  $("#headerLogoB").css("z-index", 2);
			  $("#headerLogoR").fadeIn(5000, function(){
				  $("#headerLogoB").hide();
				  FadeG();
				  });  
		  }
		  function FadeG()
		  {
			  $("#headerLogoR").css("z-index", 2);
			  $("#headerLogoG").css("z-index", 5);
			  $("#headerLogoB").css("z-index", 2);
			  $("#headerLogoG").fadeIn(5000, function(){
				  $("#headerLogoR").hide();
				  FadeB();
				  });  
		  }
		  function FadeB()
		  {
			  $("#headerLogoR").css("z-index", 2);
			  $("#headerLogoG").css("z-index", 2);
			  $("#headerLogoB").css("z-index", 5);
			  $("#headerLogoB").fadeIn(5000, function(){
				  $("#headerLogoG").hide();
				  FadeR();
				  });  
		  }