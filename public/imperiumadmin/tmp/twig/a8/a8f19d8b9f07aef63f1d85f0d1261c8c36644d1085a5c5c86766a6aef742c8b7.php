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

/* table/start_and_number_of_rows_fieldset.twig */
class __TwigTemplate_d4a43113a7a829b049f9047472249f8a33bb5e6c8b4d8778994eae112bf0c977 extends Template
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
        echo "<fieldset class=\"row g-3 align-items-center my-3\">
  <div class=\"col-auto\">
    <label class=\"col-form-label\" for=\"startRowInput\">";
echo _gettext("Start row:");
        // line 3
        echo "</label>
  </div>
  <div class=\"col-auto\">
    <input class=\"form-control\" id=\"startRowInput\" type=\"number\" name=\"pos\" min=\"0\" value=\"";
        // line 6
        echo twig_escape_filter($this->env, ($context["pos"] ?? null), "html", null, true);
        echo "\"";
        if ((1 === twig_compare(($context["unlim_num_rows"] ?? null), 0))) {
            echo " max=\"";
            echo twig_escape_filter($this->env, (($context["unlim_num_rows"] ?? null) - 1), "html", null, true);
            echo "\"";
        }
        echo " required>
  </div>
  <div class=\"col-auto\">
    <label class=\"col-form-label\" for=\"maxRowsInput\">";
echo _gettext("Number of rows:");
        // line 9
        echo "</label>
  </div>
  <div class=\"col-auto\">
    <input class=\"form-control\" id=\"maxRowsInput\" type=\"number\" name=\"session_max_rows\" min=\"1\" value=\"";
        // line 12
        echo twig_escape_filter($this->env, ($context["rows"] ?? null), "html", null, true);
        echo "\" required>
  </div>
  <div class=\"col-auto\">
    <input class=\"btn btn-primary\" type=\"submit\" name=\"submit\" value=\"";
echo _gettext("Go");
        // line 15
        echo "\">
  </div>
  <input type=\"hidden\" name=\"sql_query\" value=\"";
        // line 17
        echo twig_escape_filter($this->env, ($context["sql_query"] ?? null), "html", null, true);
        echo "\">
  <input type=\"hidden\" name=\"unlim_num_rows\" value=\"";
        // line 18
        echo twig_escape_filter($this->env, ($context["unlim_num_rows"] ?? null), "html", null, true);
        echo "\">
</fieldset>
";
    }

    public function getTemplateName()
    {
        return "table/start_and_number_of_rows_fieldset.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  80 => 18,  76 => 17,  72 => 15,  65 => 12,  60 => 9,  47 => 6,  42 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "table/start_and_number_of_rows_fieldset.twig", "/var/www/html/public/phpmyadmin/templates/table/start_and_number_of_rows_fieldset.twig");
    }
}
