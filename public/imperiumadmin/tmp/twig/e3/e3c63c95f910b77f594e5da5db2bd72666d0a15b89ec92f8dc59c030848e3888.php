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

/* database/qbe/sort_select_cell.twig */
class __TwigTemplate_fa2f9f8bc1be81c3cece782a762ad2eed61d002bbaa1028e0fa1907c5aeb561e extends Template
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
    <select style=\"width:";
        // line 2
        echo twig_escape_filter($this->env, ($context["real_width"] ?? null), "html", null, true);
        echo "\" name=\"criteriaSort[";
        echo twig_escape_filter($this->env, ($context["column_number"] ?? null), "html", null, true);
        echo "]\" size=\"1\">
        <option value=\"\">&nbsp;</option>
        <option value=\"ASC\"";
        // line 5
        echo (((0 === twig_compare(($context["selected"] ?? null), "ASC"))) ? (" selected=\"selected\"") : (""));
        echo ">";
echo _gettext("Ascending");
        echo "</option>
        <option value=\"DESC\"";
        // line 7
        echo (((0 === twig_compare(($context["selected"] ?? null), "DESC"))) ? (" selected=\"selected\"") : (""));
        echo ">";
echo _gettext("Descending");
        echo "</option>
    </select>
</td>
";
    }

    public function getTemplateName()
    {
        return "database/qbe/sort_select_cell.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 7,  47 => 5,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/qbe/sort_select_cell.twig", "/var/www/html/public/phpmyadmin/templates/database/qbe/sort_select_cell.twig");
    }
}
