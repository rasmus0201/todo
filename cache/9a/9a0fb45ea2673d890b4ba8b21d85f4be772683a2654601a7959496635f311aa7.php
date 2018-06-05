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
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
\t<meta charset=\"UTF-8\">
\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.5, minimum-scale=1.0\">
\t<title>Todo List</title>
\t<link href=\"http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Shadows+Into+Light+Two\" rel=\"stylesheet\">
\t<link rel=\"stylesheet\" href=\"assets/css/main.css\">

\t<script src=\"https://cdn.jsdelivr.net/npm/vue/dist/vue.js\"></script>
\t<script src=\"https://unpkg.com/axios/dist/axios.min.js\"></script>
\t<script type=\"text/javascript\">
\t\tbase_url = 'http://";
        // line 13
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "';
\t</script>
</head>
<body>
\t<div id=\"app\">
\t\t";
        // line 18
        $this->displayBlock('content', $context, $blocks);
        // line 19
        echo "\t</div>


\t<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js\"></script>
\t<script src=\"assets/js/main.js\"></script>
</body>
</html>
";
    }

    // line 18
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
        return array (  59 => 18,  48 => 19,  46 => 18,  38 => 13,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layouts/app.html.twig", "/Users/rasmus/http/todo/views/layouts/app.html.twig");
    }
}
