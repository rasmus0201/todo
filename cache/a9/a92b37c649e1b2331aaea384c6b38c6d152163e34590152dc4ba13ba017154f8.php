<?php

/* list.html.twig */
class __TwigTemplate_db400fa56dfd2f7c5349353871b85a35a99adf7198ea1a9dabe9d7df2481d0c7 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 2
        $this->parent = $this->loadTemplate("layouts/app.html.twig", "list.html.twig", 2);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layouts/app.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        // line 7
        echo "    <a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->pathFor("home"), "html", null, true);
        echo "\">Home</a>

    <div class=\"box list dog-ear\">
        ";
        // line 11
        echo "        <div id=\"update\" data-list-id=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["list"] ?? null), "id", array()), "html", null, true);
        echo "\" data-list-url=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["list"] ?? null), "url", array()), "html", null, true);
        echo "\">
            ";
        // line 13
        echo "            <todo-form></todo-form>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  51 => 13,  44 => 11,  37 => 7,  35 => 6,  32 => 5,  15 => 2,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "list.html.twig", "/Users/rasmus/http/todo/views/list.html.twig");
    }
}
