<?php
/* ==========================================================================
   STED AI - ask the database in plain English (modern design - backup: listapidata____28_10_2026.php)
   Sends #question to getapidata (insert() below) and shows the answer in .dataresponse
   Styles in css/pages/manage.css
   ========================================================================== */
$this->load->view('include/_page_hero', array(
    'ph_title'   => 'STED AI',
    'ph_eyebrow' => 'Search by AI',
    'ph_text'    => 'Ask a question about your courses, learners or exams in plain English. STED AI turns it into a search and lists the matching records.',
    'ph_crumbs'  => array(
        array('label' => 'Home', 'url' => base_url()),
        array('label' => 'My profile', 'url' => base_url() . 'edit-profile'),
        array('label' => 'STED AI'),
    ),
    'ph_stats'   => array(
        array('icon' => 'fas fa-wand-magic-sparkles', 'value' => 'Plain English', 'label' => 'No query language needed'),
        array('icon' => 'fas fa-shield-halved', 'value' => 'Read only', 'label' => 'Your data is never changed'),
    ),
));
$mx_ai_question = set_value("question", isset($info["question"]) ? $info["question"] : '');
?>
<div class="mx-inner mx-mg mx-ai">
    <section id="about-2" class="mx-mg-section">
        <div class="container">

            <?php $this->load->view('include/_manage_nav', array('mg_active' => 'listapidata')); ?>

            <div class="row">
                <div class="col-lg-6">
                    <div class="mx-form-card" data-reveal>
                        <div class="mx-ai-head">
                            <span class="mx-mg-ico" aria-hidden="true"><i class="fas fa-wand-magic-sparkles"></i></span>
                            <div>
                                <h2>Ask a question</h2>
                                <p>Be specific: name the course, person or date you are looking for.</p>
                            </div>
                        </div>

                        <form class="mx-form" novalidate onsubmit="mxAiAsk(); return false;">
                            <div class="mx-field" id="mx-ai-field">
                                <label for="question">Your question</label>
                                <div class="mx-input mx-input-area">
                                    <i class="far fa-comment-dots" aria-hidden="true"></i>
                                    <textarea id="question" name="question" rows="5" placeholder="e.g. Which learners completed the Electrical Power Distribution course?"><?php echo $mx_ai_question; ?></textarea>
                                </div>
                                <span class="mx-field-err">Please type a question first</span>
                            </div>

                            <div class="mx-form-actions">
                                <button type="submit" class="mx-btn mx-btn-primary mx-send">
                                    <span class="mx-send-txt">Ask STED AI</span>
                                    <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                </button>
                                <span class="mx-form-note"><i class="fas fa-keyboard"></i> Ctrl + Enter to send</span>
                            </div>
                        </form>

                        <div class="mx-ai-examples">
                            <span class="mx-ai-examples-label">Try for example</span>
                            <div class="mx-ai-examples-list">
                                <button type="button" class="mx-ai-example"><i class="fas fa-bolt"></i>List the 5 newest courses</button>
                                <button type="button" class="mx-ai-example"><i class="fas fa-bolt"></i>How many users are registered?</button>
                                <button type="button" class="mx-ai-example"><i class="fas fa-bolt"></i>Show the courses with a rating above 4</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mx-form-card mx-ai-result dataaop" data-reveal style="--d:120ms" aria-live="polite">
                        <div class="mx-ai-head">
                            <span class="mx-mg-ico is-warm" aria-hidden="true"><i class="fas fa-list-check"></i></span>
                            <div>
                                <h2>Results</h2>
                                <p>Each line is one matching record.</p>
                            </div>
                        </div>
                        <div class="mx-ai-loading" role="status"><span></span><span></span><span></span> Searching&hellip;</div>
                        <div class="mx-ai-out dataresponse" id="dataresponse">
                            <div class="mx-ai-empty">
                                <i class="fas fa-magnifying-glass-chart" aria-hidden="true"></i>
                                <b>No search yet</b>
                                Ask a question on the left and the answer will appear here.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div> <!-- END INNER PAGE WRAPPER -->

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

<script>
    // Modern UI helpers: validation hint, loading state, example questions (the request itself is insert() above)
    function mxAiAsk() {
        var $q = $('#question'), empty = $.trim($q.val()) === '';
        $('#mx-ai-field').toggleClass('has-error', empty);
        if (empty) { $q.trigger('focus'); return; }
        insert();
    }
    $(function() {
        var $card = $('.mx-ai-result'), $btn = $('.mx-ai .mx-send');
        $(document).ajaxSend(function(e, xhr, s) {
            if (s.url && s.url.indexOf('getapidata') > -1) {
                $card.addClass('is-loading');
                $btn.prop('disabled', true).find('.mx-send-txt').text('Searching...');
            }
        }).ajaxComplete(function(e, xhr, s) {
            if (s.url && s.url.indexOf('getapidata') > -1) {
                $card.removeClass('is-loading');
                $btn.prop('disabled', false).find('.mx-send-txt').text('Ask STED AI');
                if (xhr.status !== 200) $('.dataresponse').html("<div class='apidata'>The search could not be completed. Please try again.</div>");
            }
        });
        $('#question').on('input', function() {
            if ($.trim(this.value) !== '') $('#mx-ai-field').removeClass('has-error');
        }).on('keydown', function(e) {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) { e.preventDefault(); mxAiAsk(); }
        });
        $('.mx-ai-example').on('click', function() {
            $('#question').val($.trim($(this).text())).trigger('input').trigger('focus');
        });
    });
</script>
