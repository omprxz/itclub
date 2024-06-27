<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Club FAQs</title>
    <link rel="stylesheet" href="common.css" type="text/css" media="all" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">

    <style>
    .middle{
      overflow: hidden;
    }
        .faqs {
            margin: 0 auto;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            color: var(--six-color);
            overflow: hidden;
        }

        .faqs h1 {
            font-weight: 700;
            font-size: 26px;
            text-align: center;
            margin-bottom: 15px;
            color: var(--six-color);
        }

        .faq-item {
            margin-bottom: 20px;
        }

        .question {
            margin-bottom: 8px;
            font-weight: bold;
            color: #007BFF;
            cursor: pointer;
        }

        .answer {
            display: block;
            padding: 10px;
            border: 1px solid var(--thi-color);
            border-radius: 5px;
        }
    </style>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
  
    <?php include 'header.html'; ?>
    
    <section class="middle">
   <?php

$jsonData = file_get_contents('components/faqs.json');
$faqs = json_decode($jsonData, true);

$html = '<div class="faqs">' . PHP_EOL;
$html .= '    <h1>FAQs - IT Club</h1>' . PHP_EOL;

foreach ($faqs['faqs'] as $index => $faq) {
    $animationClass = ($index % 2 === 0) ? 'fade-left' : 'fade-right';
    $html .= '    <div class="faq-item" data-aos="' . $animationClass . '" data-aos-duration="600">' . PHP_EOL;
    $html .= '        <div class="question">' . $faq['question'] . '</div>' . PHP_EOL;
    $html .= '        <div class="answer">' . $faq['answer'] . '</div>' . PHP_EOL;
    $html .= '    </div>' . PHP_EOL;
}

$html .= '</div>';

echo $html;
?>
</section>
    
    <?php include 'footer.html'; ?>


    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>