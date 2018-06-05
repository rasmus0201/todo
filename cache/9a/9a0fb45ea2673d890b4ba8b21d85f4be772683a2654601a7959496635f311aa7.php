<?php

/* layouts/app.html.twig */
class __TwigTemplate_89fcbb1ce47f21dc79164b5995d49019c29e1daaa94e71791a4cfd5dd2301fba extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
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
\t<title>Todo List</title>

\t";
        // line 13
        echo "\t<link href=\"http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Shadows+Into+Light+Two\" rel=\"stylesheet\">

\t";
        // line 16
        echo "\t<link rel=\"stylesheet\" href=\"assets/css/main.css\">

\t";
        // line 19
        echo "\t<script src=\"https://cdn.jsdelivr.net/npm/vue/dist/vue.js\"></script>
\t";
        // line 21
        echo "\t<script src=\"https://unpkg.com/axios/dist/axios.min.js\"></script>

\t";
        // line 24
        echo "\t<script type=\"text/javascript\">
\t\tbase_url = 'http://";
        // line 25
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "';
\t</script>
</head>
<body>
\t";
        // line 30
        echo "\t<div id=\"app\">
\t\t";
        // line 31
        $this->displayBlock('content', $context, $blocks);
        // line 32
        echo "\t</div>
\t";
        // line 34
        echo "
\t";
        // line 36
        echo "\t<script src=\"assets/js/main.js\"></script>
</body>
</html>
";
    }

    // line 31
    public function block_content($context, array $blocks = array())
    {
        echo " ";
    }

    public function getTemplateName()
    {
        return "layouts/app.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  81 => 31,  74 => 36,  71 => 34,  68 => 32,  66 => 31,  63 => 30,  56 => 25,  53 => 24,  49 => 21,  46 => 19,  42 => 16,  38 => 13,  33 => 9,  29 => 6,  24 => 2,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layouts/app.html.twig", "/Users/rasmus/http/todo/views/layouts/app.html.twig");
    }
}
