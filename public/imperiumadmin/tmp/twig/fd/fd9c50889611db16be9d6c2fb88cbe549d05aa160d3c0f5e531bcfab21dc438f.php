<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* display/results/null_display.twig */
class __TwigTemplate_3c21b45b28a33e54d2c4b2a0bce9d23a03bf8cc40032abc8d7fce0a5e86a12ce extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<td data-decimals=\"";
        echo twig_escape_filter($this->env, ($context["data_decimals"] ?? null), "html", null, true);
        echo "\"
    data-type=\"";
        // line 2
        echo twig_escape_filter($this->env, ($context["data_type"] ?? null), "html", null, true);
        echo "\"
    ";
        // line 4
        echo "    class=\"";
        echo twig_escape_filter($this->env, ($context["classes"] ?? null), "html", null, true);
        echo " null\">
    <em>NULL</em>
</td>
";
    }

    public function getTemplateName()
    {
        return "display/results/null_display.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 4,  42 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "display/results/null_display.twig", "/var/www/html/public/imperiumadmin/templates/display/results/null_display.twig");
    }
}
