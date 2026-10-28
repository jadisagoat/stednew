<!-- INNER PAGE WRAPPER
============================================= -->	
<div class="inner-page-wrapper">
    <!-- BREADCRUMB
    ============================================= -->
    <div id="breadcrumb" class="division">
        <div class="container">
            <div class="row">

                <!-- BREADCRUMB NAV -->
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>edit-profile">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">
							   <span style="color:#00ADB1;font-size:12px"> Search by AI</span>
                           </li>
                        </ol>
                    </nav>
                </div> 

            </div>	 <!-- End row -->
        </div>	<!-- End container -->		
    </div>	<!-- END BREADCRUMB -->	

    <!-- CATEGORIES-2
    ============================================= -->
	<section id="about-2" class="about-section division">
		<div class="container pt-60 pb-30">

        <div class="row d-flex pb-3">
            <div class="col-md-5 col-lg-6 contact - top">
			    <div class="panell panell-primary">
				    <div class="panell-heading">Please enter question</div>
                    <div class="panell-body"> 
                        <textarea class="ckeditor input-xxlarge" id="question" rows="5"  name="question" ><?php echo set_value("question",$info["question"]) ;?></textarea> 
                    </div>
				</div>
                <div class="row d-flex pb-3">
                    <div class="col-md-12 d-flex align-items-center">
                        <input type="button"  value="SEND" onClick="insert()"  class="contactbutt" /> 
                    </div>
			   </div>
            </div>
            <div class="col-md-5 col-lg-6 contact - top">

            <div class="cont-ainer pt-0 pb-60 dataaop">
                <div class="panell panell-primary panell2 panell2-primary">
                    <div class="panell-heading">Results</div>
                    <div class="panell-body dataresponse panell2-body" id="dataresponse"></div>
                </div> 
		    </div>
           
            </div>
                <!-- <div class="row d-flex pb-3">
                    <div class="col-md-12 d-flex align-items-center">
                        <input type="button"  value="SEND" onClick="insert()"  class="contactbutt" /> 
                    </div>
			   </div> -->
			</div>
        </div>
        <!-- <div class="container pt-0 pb-60 dataaop">
			<div class="panell panell-primary panell2 panell2-primary">
                <div class="panell-heading">Results</div>
                <div class="panell-body dataresponse" id="dataresponse"></div>
            </div> 
		</div> -->
    </section>
</div>	<!-- END INNER PAGE WRAPPER -->

<script>
function insert() { 
    var question=$('#question').val();
	if(question=='')
	  {
		    $("#question").css({'border':'1px solid #bebdbd','color':'#132c57'});
      }else{
			$.ajax({
				type: 'POST',
				url:"<?php echo base_url()?>getapidata", 
				data: {'question':question}, 
				// dataType : "json",
			})
			.done(function(response) {
                $(".dataaop").css({'display': 'block'});

                // alert(response);
				if(response) {
                    $('.dataresponse').html(response);
                    //     // success
                    // if(response==1)
                    // 	$('.dataresponse').html("Information was updated successfully");
                    // else if(response==2)
                    // 	$('.dataresponse').html("Information was inserted successfully");
				}
				else 
                {  // error
					$('.dataresponse').html("Error in data was insert!");
				}

			}).fail(function(response) {
			console.error("Unknown Error!"); // not sure yet how I'd get here... ?
			});
   }
}
 
					</script>     

<style>
    .contactbutt {
        min-width: 103px;
    }

  
 .apidataline {
 width: 100%;
 border-bottom: 1px dashed #ff4500 !important;
 }

.dataaop {
  display:none;
}

.contactbutt {
    font-weight:normal !important
}
.panell2-primary>.panell-heading
 {
    background-color: #ff4500 !important;
    border-color: #ff4500 !important;
 }
.panell2-primary {
    border-color: #ff4500 !important;
}
.panell2 {
    border: 1px solid #ff4500 !important;
}

.apidata {
    padding: 6px 12px;
    color: #888;
    font-size: 0.95rem;
    font-family: sans-serif;
    line-height: 1.42857143;
    }

.contact-top div.label{
    padding:0 !important;
}
.contact-top div input[type="text"],
 .contact-top div textarea {
    width: 100%;
    padding: 0.7em;
    margin: 0.4em 0 0;
    background: none;
    outline: none;
    border: 0 !important;
    -webkit-appearance: none;
}
 
.panell-primary {
    border-color: #00ADB1 ;
}

.panell {
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid #00ADB1;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05) ;
    box-shadow: 0 1px 1px rgba(0,0,0,.05) ;
}
.panell2-body {
	padding:0px 20px !important;
 }
.panell-body {
	padding:0px;
    min-height: 120px;
}

.input-xxlarge {
   min-height: 120px !important; 
}

.panell-primary>div.panell-heading {
    padding: 10px 15px;
    color: #fff ;
    background-color: #00ADB1 ;
    border-color: #00ADB1 ;
}

.panell-heading {
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}
 

.input-xxlarge{
	display: block;
    width: 100%;
    /* height: 34px; */
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.contact-top div.label{
	display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 700;
}
</style>