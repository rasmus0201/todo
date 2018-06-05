<?php

/* error/404.html.twig */
class __TwigTemplate_10920a69f19629a22d97c5d5aa600a350fbf9ba539123b2213a7e2d5537cce2f extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
\t";
        // line 6
        echo "\t<meta charset=\"UTF-8\">

\t";
        // line 9
        echo "\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.5, minimum-scale=1.0\">
\t<title>Todo List - 404 Page not found</title>

\t";
        // line 13
        echo "\t<link href=\"http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Shadows+Into+Light+Two\" rel=\"stylesheet\">

\t";
        // line 16
        echo "\t<link rel=\"stylesheet\" href=\"assets/css/main.css\">
</head>
<body>
\t";
        // line 20
        echo "\t<div id=\"app\">

\t\t";
        // line 23
        echo "        <div class=\"box list dog-ear\">
            <h1 class=\"header\">404 - Page not found</h1>

            <a href=\"http://";
        // line 26
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "\">Please, go home</a>
        </div>
\t</div>
\t";
        // line 30
        echo "</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "error/404.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 30,  55 => 26,  50 => 23,  46 => 20,  41 => 16,  37 => 13,  32 => 9,  28 => 6,  23 => 2,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "error/404.html.twig", "/Users/rasmus/http/todo/views/error/404.html.twig");
    }
}
