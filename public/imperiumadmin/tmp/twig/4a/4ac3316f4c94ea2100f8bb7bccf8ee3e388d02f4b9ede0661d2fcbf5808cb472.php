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

/* database/qbe/sort_order_select_cell.twig */
class __TwigTemplate_4314a992449722dcf6fb71d245e3bfeda977bb16a2de7c763e5d6793a5ded12c extends Template
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
        echo "<td class=\"text-center\">
    <select name=\"criteriaSortOrder[";
        // line 2
        echo twig_escape_filter($this->env, ($context["column_number"] ?? null), "html", null, true);
        echo "]\">
        <option value=\"1000\">&nbsp;</option>
        ";
        // line 4
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(1, ($context["total_column_count"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            // line 5
            echo "            <option value=\"";
            echo twig_escape_filter($this->env, $context["i"], "html", null, true);
            echo "\"";
            echo (((0 === twig_compare($context["i"], ($context["sort_order"] ?? null)))) ? (" selected=\"selected\"") : (""));
            echo ">
                ";
            // line 6
            echo twig_escape_filter($this->env, $context["i"], "html", null, true);
            echo "
            </option>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 9
        echo "    </select>
</td>
";
    }

    public function getTemplateName()
    {
        return "database/qbe/sort_order_select_cell.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 9,  56 => 6,  49 => 5,  45 => 4,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/qbe/sort_order_select_cell.twig", "/var/www/html/public/phpmyadmin/templates/database/qbe/sort_order_select_cell.twig");
    }
}
