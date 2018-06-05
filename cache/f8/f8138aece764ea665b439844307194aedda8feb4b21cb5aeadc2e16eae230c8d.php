<?php

/* home.html.twig */
class __TwigTemplate_b3a43b4336764e9426a8edb97a276169ed1422a338616b4ca321594d3871a9a8 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 2
        $this->parent = $this->loadTemplate("layouts/app.html.twig", "home.html.twig", 2);
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
        echo "    <div class=\"box list dog-ear\">

        ";
        // line 10
        echo "        <div id=\"create\" v-on:click.prevent=\"create_list\">
            <h1 class=\"header\">Todo List</h1>
            <form class=\"item-add\">
                <input type=\"text\" name=\"name\" placeholder=\"Skriv en ny to-do her.\" class=\"input\" autocomplete=\"off\" required>
                <input type=\"submit\" value=\"Tilføj\" class=\"submit\">
            </form>
        </div>
    </div>
    ";
        // line 19
        echo "
    ";
        // line 21
        echo "    ";
        if (twig_length_filter($this->env, ($context["lists"] ?? null))) {
            // line 22
            echo "        <div class=\"box list dog-ear\">
            <h1 class=\"header\">Offentlige Todo Liste</h1>
            <ul>
                ";
            // line 26
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["lists"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["list"]) {
                // line 27
                echo "                    <li><a href=\"";
                echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->pathFor("list", array("url" => twig_get_attribute($this->env, $this->source, $context["list"], "url", array()))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["list"], "name", array()), "html", null, true);
                echo "</a></li>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['list'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 29
            echo "            </ul>
        </div>
    ";
        }
    }

    public function getTemplateName()
    {
        return "home.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 29,  67 => 27,  62 => 26,  57 => 22,  54 => 21,  51 => 19,  41 => 10,  37 => 7,  35 => 6,  32 => 5,  15 => 2,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "home.html.twig", "/Users/rasmus/http/todo/views/home.html.twig");
    }
}
