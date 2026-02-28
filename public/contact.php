<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

$contactError = '';
if (isset($_SESSION['contact_error']) && is_string($_SESSION['contact_error'])) {
    $contactError = $_SESSION['contact_error'];
    unset($_SESSION['contact_error']);
}

$oldName = '';
if (isset($_SESSION['contact_old_name']) && is_string($_SESSION['contact_old_name'])) {
    $oldName = $_SESSION['contact_old_name'];
    unset($_SESSION['contact_old_name']);
}

$oldMessage = '';
if (isset($_SESSION['contact_old_message']) && is_string($_SESSION['contact_old_message'])) {
    $oldMessage = $_SESSION['contact_old_message'];
    unset($_SESSION['contact_old_message']);
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact</title>
    <style>
        :root {
            --bg: #f2f6f8;
            --card: #ffffff;
            --ink: #1f2b37;
            --muted: #5e6b78;
            --line: #d3dde5;
            --accent: #0b5e8a;
            --accent-hover: #084867;
            --error-bg: #fff3f3;
            --error-line: #efc2c2;
            --error-text: #8a2a2a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--ink);
            font-family: "BIZ UDPGothic", "Hiragino Kaku Gothic ProN", sans-serif;
            background: radial-gradient(circle at 10% 10%, #ffffff 0%, var(--bg) 48%, #e7eef3 100%);
        }

        .wrap {
            max-width: 760px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .hero {
            margin-bottom: 22px;
        }

        h1 {
            margin: 0 0 14px;
            font-family: "Shippori Mincho B1", "Yu Mincho", serif;
            font-size: 30px;
            letter-spacing: 0.04em;
        }

        .hero p {
            margin: 0 0 10px;
            color: var(--muted);
            line-height: 1.7;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(31, 43, 55, 0.08);
        }

        label {
            display: inline-block;
            margin-bottom: 6px;
            font-weight: 700;
        }

        input,
        textarea {
            width: 100%;
            font: inherit;
            padding: 10px 12px;
            border: 1px solid #b7c6d2;
            border-radius: 8px;
            background: #fcfdff;
        }

        textarea {
            min-height: 180px;
            resize: vertical;
        }

        .field {
            margin-bottom: 16px;
        }

        .error {
            margin: 0 0 16px;
            padding: 12px;
            background: var(--error-bg);
            border: 1px solid var(--error-line);
            color: var(--error-text);
            border-radius: 8px;
        }

        button {
            appearance: none;
            border: 0;
            border-radius: 999px;
            padding: 10px 20px;
            color: #fff;
            background: var(--accent);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        button:hover {
            background: var(--accent-hover);
        }

        @media (max-width: 600px) {
            .wrap {
                padding: 26px 14px;
            }

            h1 {
                font-size: 24px;
            }

            .card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="hero">
            <h1>お問い合わせフォーム</h1>
            <p>お客様からのご意見・ご要望をお待ちしております。</p>
            <p>サービス向上のため、気づいた点やご不明点などがございましたら、お気軽にお寄せください。</p>
        </div>

        <div class="card">
            <?php if ($contactError !== ''): ?>
                <p class="error"><?php echo htmlspecialchars($contactError, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <form action="/contact_submit.php" method="post">
                <div class="field">
                    <label for="name">お名前</label>
                    <input id="name" name="name" type="text" required value="<?php echo htmlspecialchars($oldName, ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div class="field">
                    <label for="message">お問い合わせ内容</label>
                    <textarea id="message" name="message" required><?php echo htmlspecialchars($oldMessage, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <button type="submit">送信する</button>
            </form>
        </div>
    </div>
</body>
</html>
