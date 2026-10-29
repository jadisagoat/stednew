<?php
/* Back office login (redesign - backup: login____29_10_2026.php).
   Form fields, action and validation are unchanged. */
$cond = array('admin_id' => 2);
$this->db->select('image,website_title');
$title_site = $this->fct->getonerow('admin', $cond);
$bo_base  = base_url();
$bo_other = bo_lang() === 'ar' ? 'en' : 'ar';
$bo_site  = htmlspecialchars((string) $title_site["website_title"], ENT_QUOTES, 'UTF-8');
$bo_logo  = $bo_base . 'uploads/website/' . rawurlencode((string) $title_site["image"]);
$bo_has_error = $this->session->userdata('login_error') || validation_errors() !== '';
?>
<!DOCTYPE html>
<html lang="<?= bo_lang() ?>" dir="<?= bo_rtl() ? 'rtl' : 'ltr' ?>">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="robots" content="noindex, nofollow" />
<title><?= $bo_site ?> - <?= bo_t('login_title') ?></title>
<link rel="icon" type="image/x-icon" href="<?= $bo_base ?>sted_theme/assets/img/favicon/favicon.ico" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/fonts/boxicons.css" />
<style>
:root{
  --teal:#00adb1; --teal-dark:#008f93; --navy:#24385f; --navy-deep:#16243f;
  --orange:#f58a3c; --ink:#1f2b3d; --muted:#6b7a8c; --line:#dde3ea;
  --field:#f7f9fb; --danger:#d64545; --danger-bg:#fdeeee; --bg:#eef2f5;
}
*{box-sizing:border-box}
html,body{height:100%}
body{margin:0;font-family:"Cairo","Segoe UI",Tahoma,sans-serif;font-size:15px;color:var(--ink);background:var(--bg);
  display:flex;align-items:center;justify-content:center;padding:24px 16px;-webkit-font-smoothing:antialiased}
a{color:var(--teal-dark);text-decoration:none}
a:hover{text-decoration:underline}
:focus-visible{outline:3px solid rgba(0,173,177,.35);outline-offset:2px}

.auth{width:100%;max-width:960px;display:grid;grid-template-columns:1fr 1fr;background:#fff;border-radius:18px;overflow:hidden;
  box-shadow:0 24px 60px -20px rgba(22,36,63,.28),0 2px 6px rgba(22,36,63,.06)}

/* Brand panel */
.panel{position:relative;color:#fff;padding:44px 40px;display:flex;flex-direction:column;gap:28px;
  background:linear-gradient(150deg,var(--navy-deep) 0%,var(--navy) 45%,var(--teal-dark) 100%);overflow:hidden}
.panel::before,.panel::after{content:"";position:absolute;border-radius:50%;pointer-events:none}
.panel::before{width:320px;height:320px;inset-block-end:-140px;inset-inline-end:-110px;border:48px solid rgba(255,255,255,.06)}
.panel::after{width:10px;height:10px;inset-block-start:44px;inset-inline-end:40px;background:var(--orange);box-shadow:0 0 0 6px rgba(245,138,60,.18)}
.panel > *,.form-side{min-width:0}
.logo{display:inline-flex;align-items:center;gap:12px;background:#fff;border-radius:12px;padding:8px 14px;align-self:flex-start;position:relative;z-index:1}
.logo img{height:38px;width:auto;display:block}
.logo span{font-weight:700;color:var(--navy);font-size:15px}
.panel-body{position:relative;z-index:1;margin-top:auto}
.eyebrow{display:inline-block;font-size:12px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#8fe6e8;margin-bottom:10px}
.panel h1{font-size:26px;line-height:1.45;margin:0 0 10px;font-weight:700}
.panel p{margin:0;color:rgba(255,255,255,.78);line-height:1.8;max-width:38ch}
.feats{list-style:none;margin:0;padding:0;display:grid;gap:12px;position:relative;z-index:1;margin-bottom:auto}
.feats li{display:flex;align-items:center;gap:12px;font-size:14px;color:rgba(255,255,255,.92)}
.feats i{width:34px;height:34px;flex:0 0 34px;border-radius:9px;display:grid;place-items:center;font-size:18px;background:rgba(255,255,255,.1);color:#8fe6e8}

/* Form side */
.form-side{padding:48px 44px 32px;display:flex;flex-direction:column}
.form-top{display:flex;justify-content:flex-end;margin-bottom:28px}
.lang{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--muted);border:1px solid var(--line);border-radius:999px;padding:5px 12px}
.lang:hover{color:var(--teal-dark);border-color:var(--teal);text-decoration:none}
.lang i{font-size:16px}
h2{font-size:26px;margin:0 0 6px;font-weight:700;color:var(--navy)}
.intro{margin:0 0 26px;color:var(--muted)}

.alert{display:flex;gap:10px;align-items:flex-start;background:var(--danger-bg);color:var(--danger);border-radius:10px;padding:11px 14px;font-size:14px;margin-bottom:18px}
.alert::before{content:"\ea3a";font-family:"boxicons";font-size:18px;line-height:1.3}
.alert p{margin:0}

.field{margin-bottom:18px}
.field label{display:block;font-size:13px;font-weight:600;color:var(--ink);margin-bottom:7px}
.control{position:relative;display:flex;align-items:center}
.control > i{position:absolute;inset-inline-start:14px;font-size:19px;color:#9aa7b6;pointer-events:none;transition:color .15s}
.control input{width:100%;height:48px;border:1.5px solid var(--line);background:var(--field);border-radius:11px;font:inherit;color:var(--ink);
  padding-inline:44px 14px;transition:border-color .15s,background .15s,box-shadow .15s}
.control input::placeholder{color:#a9b4c1}
.control input:focus{outline:none;border-color:var(--teal);background:#fff;box-shadow:0 0 0 4px rgba(0,173,177,.12)}
.control:focus-within > i{color:var(--teal)}
.has-error .control input{border-color:#f0b4b4}
.control input.pw{padding-inline-end:46px}
.toggle{position:absolute;inset-inline-end:6px;width:36px;height:36px;border:0;background:transparent;border-radius:8px;cursor:pointer;color:#8a97a6;font-size:19px;display:grid;place-items:center}
.toggle:hover{color:var(--teal-dark);background:rgba(0,173,177,.08)}
.caps{display:none;margin-top:7px;font-size:12.5px;color:#b86e14}
.caps.on{display:flex;align-items:center;gap:5px}

.btn{width:100%;height:50px;border:0;border-radius:11px;background:var(--teal);color:#fff;font:inherit;font-weight:700;font-size:16px;cursor:pointer;
  display:flex;align-items:center;justify-content:center;gap:8px;margin-top:8px;box-shadow:0 10px 20px -10px rgba(0,173,177,.7);transition:background .15s,transform .1s}
.btn:hover{background:var(--teal-dark)}
.btn:active{transform:translateY(1px)}
.btn i{font-size:20px}
[dir="rtl"] .btn .bx-right-arrow-alt{transform:scaleX(-1)}
.btn[disabled]{opacity:.8;cursor:wait}
.spin{width:18px;height:18px;border:2.5px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}

.form-foot{margin-top:auto;padding-top:28px;display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;font-size:13px;color:var(--muted)}
.form-foot a{display:inline-flex;align-items:center;gap:4px;color:var(--muted)}
.form-foot a:hover{color:var(--teal-dark)}
[dir="rtl"] .form-foot .bx-left-arrow-alt{transform:scaleX(-1)}

@media (max-width:820px){
  body{padding:16px 12px;align-items:flex-start}
  .auth{grid-template-columns:minmax(0,1fr);max-width:460px}
  .panel{padding:26px 26px 24px;gap:16px}
  .panel h1{font-size:19px}
  .panel p,.feats{display:none}
  .panel-body{margin-top:0}
  .form-side{padding:30px 26px 24px}
  .form-top{margin-bottom:18px}
}
@media (prefers-reduced-motion:reduce){*{transition:none!important;animation-duration:1.5s!important}}
</style>
</head>
<body>
<main class="auth">

  <aside class="panel">
    <a class="logo" href="<?= $bo_base ?>" title="<?= $bo_site ?>">
      <img src="<?= $bo_logo ?>" alt="<?= $bo_site ?>" onerror="this.src='<?= $bo_base ?>uploads/website/45x45/<?= rawurlencode((string) $title_site["image"]) ?>'" />
    </a>
    <div class="panel-body">
      <span class="eyebrow"><?= bo_t('login_panel_eyebrow') ?></span>
      <h1><?= bo_t('login_panel_title') ?></h1>
      <p><?= bo_t('login_panel_text') ?></p>
    </div>
    <ul class="feats">
      <li><i class="bx bx-book-open"></i><?= bo_t('login_feat_courses') ?></li>
      <li><i class="bx bx-badge-check"></i><?= bo_t('login_feat_exams') ?></li>
      <li><i class="bx bx-bar-chart-alt-2"></i><?= bo_t('login_feat_reports') ?></li>
    </ul>
  </aside>

  <section class="form-side">
    <div class="form-top">
      <a class="lang" href="<?= $bo_base ?>back_office/home/lang/<?= $bo_other ?>"><i class="bx bx-globe"></i><?= bo_t('nav_lang_switch') ?></a>
    </div>

    <h2><?= bo_t('login_welcome') ?></h2>
    <p class="intro"><?= bo_t('login_intro') ?></p>

    <?php echo validation_errors('<div class="alert" role="alert"><p>', '</p></div>'); ?>
    <?php if ($this->session->userdata('login_error')) {
        echo '<div class="alert" role="alert"><p>' . htmlspecialchars(bo_t('login_error'), ENT_QUOTES, 'UTF-8') . '</p></div>';
    } ?>

    <form action="<?= $bo_base ?>back_office/home/login_validate" method="post" name="login-form" id="bo-login" novalidate>
      <div class="field<?= $bo_has_error ? ' has-error' : '' ?>">
        <label for="bo-username"><?= bo_t('login_username') ?></label>
        <div class="control">
          <i class="bx bx-user"></i>
          <input type="text" id="bo-username" name="username" value="<?= set_value('username'); ?>" placeholder="<?= bo_t('login_username') ?>" autocomplete="username" autofocus />
        </div>
      </div>

      <div class="field<?= $bo_has_error ? ' has-error' : '' ?>">
        <label for="bo-password"><?= bo_t('login_password') ?></label>
        <div class="control">
          <i class="bx bx-lock-alt"></i>
          <input type="password" id="bo-password" class="pw" name="pass" value="" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" autocomplete="current-password" />
          <button type="button" class="toggle" id="bo-toggle" aria-label="<?= bo_t('login_show_password') ?>" aria-pressed="false"><i class="bx bx-hide"></i></button>
        </div>
        <div class="caps" id="bo-caps"><i class="bx bx-error-circle"></i><?= bo_t('login_caps') ?></div>
      </div>

      <button type="submit" class="btn" id="bo-submit"><span><?= bo_t('login_submit') ?></span><i class="bx bx-right-arrow-alt"></i></button>
    </form>

    <div class="form-foot">
      <a href="<?= $bo_base ?>"><i class="bx bx-left-arrow-alt"></i><?= bo_t('login_back_site') ?></a>
      <span>&copy; <?= date('Y') ?> <?= $bo_site ?></span>
    </div>
  </section>

</main>
<script>
(function () {
  var pw = document.getElementById('bo-password'),
      tg = document.getElementById('bo-toggle'),
      caps = document.getElementById('bo-caps'),
      form = document.getElementById('bo-login'),
      btn = document.getElementById('bo-submit');

  tg.addEventListener('click', function () {
    var show = pw.type === 'password';
    pw.type = show ? 'text' : 'password';
    tg.setAttribute('aria-pressed', show ? 'true' : 'false');
    tg.firstElementChild.className = show ? 'bx bx-show' : 'bx bx-hide';
    pw.focus();
  });

  function checkCaps(e) {
    if (e.getModifierState) caps.classList.toggle('on', e.getModifierState('CapsLock'));
  }
  pw.addEventListener('keyup', checkCaps);
  pw.addEventListener('keydown', checkCaps);
  pw.addEventListener('blur', function () { caps.classList.remove('on'); });

  form.addEventListener('submit', function () {
    btn.disabled = true;
    btn.innerHTML = '<span class="spin"></span><span><?= addslashes(bo_t('login_signing_in')) ?></span>';
  });
})();
</script>
</body>
</html>
<?php $this->session->unset_userdata('login_error'); ?>
