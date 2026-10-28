<?php
/* ==========================================================================
   CHATBOT (modern design - backup: chatbot____28_10_2026.php)
   The request logic below is unchanged; only the answer is now shown in the
   chat window instead of being echoed at the top of the page.
   Form: POST to chatbot with the field "user_input".
   ========================================================================== */
$mx_asked = '';
$mx_reply = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = htmlspecialchars($_POST['user_input']);

    $url = 'AI_SERVICE_ENDPOINT'; // Replace with the actual endpoint of the AI service
    // Key lives in application/config/secrets.php (not committed)
    $secrets = is_file(APPPATH . 'config/secrets.php') ? include APPPATH . 'config/secrets.php' : array();
    $apiKey = isset($secrets['chatbot_api_key']) ? $secrets['chatbot_api_key'] : '';

    // Setup the HTTP request headers and payload
    $headers = [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ];

    $postData = json_encode(['prompt' => $userInput, 'other_parameters' => 'values']); // Customize this payload as per the AI service's API documentation

    // Initialize cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Execute the cURL session and close it
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response from the AI service
    $decodedResponse = json_decode($response, true);

    // Handle the response (this will vary based on the AI service and your application's logic)
    // For example, if this is a chatbot, you might display the AI's response back to the user
    $mx_asked = $userInput;
    $mx_reply = (is_array($decodedResponse) && isset($decodedResponse['ai_response_key'])) ? $decodedResponse['ai_response_key'] : ''; // Replace 'ai_response_key' with the actual key that contains the response in the AI service's response JSON
}

$this->load->view('include/_page_hero', array(
	'ph_title'   => 'Ask the STED assistant',
	'ph_eyebrow' => 'AI assistant',
	'ph_text'    => 'Quick answers about our courses, exams, certificates and your account - any time of the day.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'Assistant'),
	),
	'ph_stats'   => array(
		array('icon' => 'fas fa-bolt', 'value' => '24/7', 'label' => 'Always available'),
		array('icon' => 'fas fa-headset', 'value' => 'Human help', 'label' => 'When you need it'),
	),
));
$mx_suggest = array(
	'How do I enroll in a course?',
	'How do exams and certificates work?',
	'Which courses do you offer for commissioning?',
	'I forgot my password',
);
?>

<div class="mx-inner mx-chatpage">
	<section class="mx-chat-section">
		<div class="container">
			<div class="row">

				<div class="col-lg-8">
					<div class="mx-chat" id="mx-chat" data-reveal>
						<div class="mx-chat-top">
							<span class="mx-chat-avatar"><i class="fas fa-robot"></i></span>
							<span><b>STED Assistant</b><small>Online &middot; usually answers in seconds</small></span>
							<a class="mx-chat-new" href="<?php echo base_url(); ?>chatbot" title="New conversation" aria-label="New conversation"><i class="fas fa-plus"></i></a>
						</div>

						<div class="mx-chat-log" aria-live="polite">
							<div class="mx-chat-msg">
								<i class="fas fa-robot" aria-hidden="true"></i>
								<div class="mx-chat-bubble">
									<p><b>Hi there!</b> I'm the STED assistant.</p>
									<p>Ask me anything about our training courses, exams, certificates or your account.</p>
								</div>
							</div>

							<?php if ($mx_reply !== null) { ?>
								<?php if (trim($mx_asked) !== '') { ?>
									<div class="mx-chat-msg is-me">
										<i class="fas fa-user" aria-hidden="true"></i>
										<div class="mx-chat-bubble"><?php echo nl2br($mx_asked); ?></div>
									</div>
								<?php } ?>
								<?php if (is_string($mx_reply) && trim($mx_reply) !== '') { ?>
									<div class="mx-chat-msg">
										<i class="fas fa-robot" aria-hidden="true"></i>
										<div class="mx-chat-bubble"><?php echo nl2br(htmlspecialchars($mx_reply, ENT_QUOTES, 'UTF-8')); ?></div>
									</div>
								<?php } else { ?>
									<div class="mx-chat-msg is-warn">
										<i class="fas fa-plug-circle-exclamation" aria-hidden="true"></i>
										<div class="mx-chat-bubble">
											<p><b>Sorry, I can't answer right now.</b></p>
											<p>The assistant is not available at the moment. Please check our <a href="<?php echo base_url(); ?>faqs">FAQs</a> or <a href="<?php echo base_url(); ?>contactus">contact our team</a> - we're happy to help.</p>
										</div>
									</div>
								<?php } ?>
							<?php } ?>

							<div class="mx-chat-msg mx-chat-typing" aria-hidden="true">
								<i class="fas fa-robot"></i>
								<div class="mx-chat-bubble"><span class="mx-chat-dots"><span></span><span></span><span></span></span></div>
							</div>
						</div>

						<div class="mx-chat-suggest" aria-label="Suggested questions">
							<?php foreach ($mx_suggest as $q) { ?>
								<button type="button" class="mx-chip" data-q="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?></button>
							<?php } ?>
						</div>

						<form class="mx-form mx-chat-form" action="<?php echo base_url(); ?>chatbot" method="post">
							<div class="mx-input">
								<i class="far fa-comment-dots" aria-hidden="true"></i>
								<input type="text" name="user_input" placeholder="Ask me something..." autocomplete="off" required aria-label="Your question" maxlength="1000">
							</div>
							<button type="submit" class="mx-btn mx-btn-primary mx-chat-send" aria-label="Send"><i class="fas fa-paper-plane"></i></button>
						</form>
						<p class="mx-chat-note"><i class="fas fa-shield-halved"></i>Please don't share passwords or personal data in the chat.</p>
					</div>
				</div>

				<div class="col-lg-4">
					<aside class="mx-chat-side">
						<div class="mx-info-card" data-reveal style="--d:120ms">
							<span class="mx-info-dots" aria-hidden="true"></span>
							<h3>What can I ask?</h3>
							<ul class="mx-info-list">
								<li><i class="fas fa-book-open"></i><span><b>Courses</b><small>Topics, duration and how to enroll</small></span></li>
								<li><i class="fas fa-certificate"></i><span><b>Exams &amp; certificates</b><small>Attempts, results and downloads</small></span></li>
								<li><i class="fas fa-user-gear"></i><span><b>Your account</b><small>Login, password and profile</small></span></li>
							</ul>
							<a class="mx-btn mx-btn-glass" href="<?php echo base_url(); ?>faqs">Read the FAQs <i class="fas fa-arrow-right"></i></a>
							<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Talk to a person <i class="fas fa-arrow-right"></i></a>
						</div>
					</aside>
				</div>

			</div>
		</div>
	</section>
</div>

<script>
	(function() {
		var chat = document.getElementById('mx-chat');
		if (!chat) return;
		var form = chat.querySelector('.mx-chat-form'),
			input = form.querySelector('input[name="user_input"]'),
			log = chat.querySelector('.mx-chat-log');
		// suggested questions fill the field
		chat.querySelectorAll('.mx-chat-suggest [data-q]').forEach(function(b) {
			b.addEventListener('click', function() { input.value = b.getAttribute('data-q'); input.focus(); });
		});
		// show the typing dots while the page posts
		form.addEventListener('submit', function() {
			if (!input.value.trim()) return;
			chat.classList.add('is-sending');
			log.scrollTop = log.scrollHeight;
		});
		log.scrollTop = log.scrollHeight;
	})();
</script>
