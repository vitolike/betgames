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

/* table/chart/tbl_chart.twig */
class __TwigTemplate_4a02dea6dbb1209f3dcc8a18db291bb0f2fc0eed6f6b3f57f0174522e99ae548 extends Template
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
        echo "<div id=\"div_view_options\" class=\"container-fluid\">
  <h2>";
echo _gettext("Display chart");
        // line 2
        echo "</h2>

  <div class=\"card\">
    <div class=\"card-body\">
      <form method=\"post\" id=\"tblchartform\" action=\"";
        // line 6
        echo PhpMyAdmin\Url::getFromRoute("/table/chart");
        echo "\" class=\"ajax\">
        ";
        // line 7
        echo PhpMyAdmin\Url::getHiddenInputs(($context["url_params"] ?? null));
        echo "

        <fieldset class=\"mb-3\" role=\"radiogroup\">
          <legend class=\"visually-hidden\">";
echo _gettext("Chart type");
        // line 10
        echo "</legend>
          <div class=\"form-check form-check-inline\">
            <input class=\"form-check-input\" type=\"radio\" name=\"chartType\" value=\"bar\" id=\"barChartTypeRadio\">
            <label class=\"form-check-label\" for=\"barChartTypeRadio\">";
echo _pgettext("Chart type", "Bar");
        // line 13
        echo "</label>
          </div>
          <div class=\"form-check form-check-inline\">
            <input class=\"form-check-input\" type=\"radio\" name=\"chartType\" value=\"column\" id=\"columnChartTypeRadio\">
            <label class=\"form-check-label\" for=\"columnChartTypeRadio\">";
echo _pgettext("Chart type", "Column");
        // line 17
        echo "</label>
          </div>
          <div class=\"form-check form-check-inline\">
            <input class=\"form-check-input\" type=\"radio\" name=\"chartType\" value=\"line\" id=\"lineChartTypeRadio\" checked>
            <label class=\"form-check-label\" for=\"lineChartTypeRadio\">";
echo _pgettext("Chart type", "Line");
        // line 21
        echo "</label>
          </div>
          <div class=\"form-check form-check-inline\">
            <input class=\"form-check-input\" type=\"radio\" name=\"chartType\" value=\"spline\" id=\"splineChartTypeRadio\">
            <label class=\"form-check-label\" for=\"splineChartTypeRadio\">";
echo _pgettext("Chart type", "Spline");
        // line 25
        echo "</label>
          </div>
          <div class=\"form-check form-check-inline\">
            <input class=\"form-check-input\" type=\"radio\" name=\"chartType\" value=\"area\" id=\"areaChartTypeRadio\">
            <label class=\"form-check-label\" for=\"areaChartTypeRadio\">";
echo _pgettext("Chart type", "Area");
        // line 29
        echo "</label>
          </div>
          <div class=\"form-check form-check-inline d-none\" id=\"pieChartType\">
            <input class=\"form-check-input\" type=\"radio\" name=\"chartType\" value=\"pie\" id=\"pieChartTypeRadio\">
            <label class=\"form-check-label\" for=\"pieChartTypeRadio\">";
echo _pgettext("Chart type", "Pie");
        // line 33
        echo "</label>
          </div>
          <div class=\"form-check form-check-inline d-none\" id=\"timelineChartType\">
            <input class=\"form-check-input\" type=\"radio\" name=\"chartType\" value=\"timeline\" id=\"timelineChartTypeRadio\">
            <label class=\"form-check-label\" for=\"timelineChartTypeRadio\">";
echo _pgettext("Chart type", "Timeline");
        // line 37
        echo "</label>
          </div>
          <div class=\"form-check form-check-inline d-none\" id=\"scatterChartType\">
            <input class=\"form-check-input\" type=\"radio\" name=\"chartType\" value=\"scatter\" id=\"scatterChartTypeRadio\">
            <label class=\"form-check-label\" for=\"scatterChartTypeRadio\">";
echo _pgettext("Chart type", "Scatter");
        // line 41
        echo "</label>
          </div>
        </fieldset>

        <div class=\"form-check mb-3 d-none\" id=\"barStacked\">
          <input class=\"form-check-input\" type=\"checkbox\" name=\"barStackedCheckbox\" value=\"1\" id=\"barStackedCheckbox\">
          <label class=\"form-check-label\" for=\"barStackedCheckbox\">";
echo _gettext("Stacked");
        // line 47
        echo "</label>
        </div>

        <div class=\"mb-3\">
          <label class=\"form-label\" for=\"chartTitleInput\">";
echo _gettext("Chart title:");
        // line 51
        echo "</label>
          <input class=\"form-control\" type=\"text\" name=\"chartTitleInput\" id=\"chartTitleInput\">
        </div>

        ";
        // line 55
        $context["xaxis"] = null;
        // line 56
        echo "        <div class=\"mb-3\">
          <label class=\"form-label\" for=\"chartXAxisSelect\">";
echo _gettext("X-Axis:");
        // line 57
        echo "</label>
          <select class=\"form-select\" name=\"chartXAxisSelect\" id=\"chartXAxisSelect\">
            ";
        // line 59
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["keys"] ?? null));
        foreach ($context['_seq'] as $context["idx"] => $context["key"]) {
            // line 60
            echo "              ";
            if ((($context["xaxis"] ?? null) === null)) {
                // line 61
                echo "                ";
                $context["xaxis"] = $context["idx"];
                // line 62
                echo "              ";
            }
            // line 63
            echo "              ";
            if ((($context["xaxis"] ?? null) === $context["idx"])) {
                // line 64
                echo "                <option value=\"";
                echo twig_escape_filter($this->env, $context["idx"], "html", null, true);
                echo "\" selected>";
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "</option>
              ";
            } else {
                // line 66
                echo "                <option value=\"";
                echo twig_escape_filter($this->env, $context["idx"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "</option>
              ";
            }
            // line 68
            echo "            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['idx'], $context['key'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 69
        echo "          </select>
        </div>

        <div class=\"mb-3\">
          <label class=\"form-label\" for=\"chartSeriesSelect\">";
echo _gettext("Series:");
        // line 73
        echo "</label>
          <select class=\"form-select resize-vertical\" name=\"chartSeriesSelect\" id=\"chartSeriesSelect\" multiple>
            ";
        // line 75
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["keys"] ?? null));
        foreach ($context['_seq'] as $context["idx"] => $context["key"]) {
            // line 76
            echo "              ";
            if (twig_get_attribute($this->env, $this->source, (($__internal_compile_0 = ($context["fields_meta"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[$context["idx"]] ?? null) : null), "isNumericType", [], "method", false, false, false, 76)) {
                // line 77
                echo "                ";
                if (((0 === twig_compare($context["idx"], ($context["xaxis"] ?? null))) && ($context["table_has_a_numeric_column"] ?? null))) {
                    // line 78
                    echo "                  <option value=\"";
                    echo twig_escape_filter($this->env, $context["idx"], "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                    echo "</option>
                ";
                } else {
                    // line 80
                    echo "                  <option value=\"";
                    echo twig_escape_filter($this->env, $context["idx"], "html", null, true);
                    echo "\" selected>";
                    echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                    echo "</option>
                ";
                }
                // line 82
                echo "              ";
            }
            // line 83
            echo "            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['idx'], $context['key'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 84
        echo "          </select>
        </div>

        <input type=\"hidden\" name=\"dateTimeCols\" value=\"";
        // line 88
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["keys"] ?? null));
        foreach ($context['_seq'] as $context["idx"] => $context["key"]) {
            // line 89
            if (twig_get_attribute($this->env, $this->source, (($__internal_compile_1 = ($context["fields_meta"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1[$context["idx"]] ?? null) : null), "isDateTimeType", [], "method", false, false, false, 89)) {
                // line 90
                echo twig_escape_filter($this->env, ($context["idx"] . " "), "html", null, true);
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['idx'], $context['key'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 92
        echo "\">
        <input type=\"hidden\" name=\"numericCols\" value=\"";
        // line 94
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["keys"] ?? null));
        foreach ($context['_seq'] as $context["idx"] => $context["key"]) {
            // line 95
            if (twig_get_attribute($this->env, $this->source, (($__internal_compile_2 = ($context["fields_meta"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2[$context["idx"]] ?? null) : null), "isNumericType", [], "method", false, false, false, 95)) {
                // line 96
                echo twig_escape_filter($this->env, ($context["idx"] . " "), "html", null, true);
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['idx'], $context['key'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 98
        echo "\">

        <div class=\"mb-3\">
          <label class=\"form-label\" for=\"xAxisLabelInput\">";
echo _gettext("X-Axis label:");
        // line 101
        echo "</label>
          <input class=\"form-control\" type=\"text\" name=\"xAxisLabelInput\" id=\"xAxisLabelInput\" value=\"";
        // line 102
        echo twig_escape_filter($this->env, (((0 === twig_compare(($context["xaxis"] ?? null),  -1))) ? (_gettext("X Values")) : ((($__internal_compile_3 = ($context["keys"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3[($context["xaxis"] ?? null)] ?? null) : null))), "html", null, true);
        echo "\">
        </div>

        <div class=\"mb-3\">
          <label class=\"form-label\" for=\"yAxisLabelInput\">";
echo _gettext("Y-Axis label:");
        // line 106
        echo "</label>
          <input class=\"form-control\" type=\"text\" name=\"yAxisLabelInput\" id=\"yAxisLabelInput\" value=\"";
echo _gettext("Y Values");
        // line 107
        echo "\">
        </div>

        <div class=\"form-check mb-3\">
          <input class=\"form-check-input\" type=\"checkbox\" id=\"seriesColumnCheckbox\" name=\"seriesColumnCheckbox\" value=\"1\">
          <label class=\"form-check-label\" for=\"seriesColumnCheckbox\">";
echo _gettext("Series names are in a column");
        // line 112
        echo "</label>
        </div>

        <div class=\"mb-3\">
          <label class=\"form-label\" for=\"chartSeriesColumnSelect\">";
echo _gettext("Series column:");
        // line 116
        echo "</label>
          <select class=\"form-select\" name=\"chartSeriesColumnSelect\" id=\"chartSeriesColumnSelect\" disabled>
            ";
        // line 118
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["keys"] ?? null));
        foreach ($context['_seq'] as $context["idx"] => $context["key"]) {
            // line 119
            echo "              <option value=\"";
            echo twig_escape_filter($this->env, $context["idx"], "html", null, true);
            echo "\"";
            echo (((0 === twig_compare($context["idx"], 1))) ? (" selected") : (""));
            echo ">";
            echo twig_escape_filter($this->env, $context["key"], "html", null, true);
            echo "</option>
              ";
            // line 120
            $context["series_column"] = $context["idx"];
            // line 121
            echo "            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['idx'], $context['key'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 122
        echo "          </select>
        </div>

        <div class=\"mb-3\">
          <label class=\"form-label\" for=\"chartValueColumnSelect\">";
echo _gettext("Value Column:");
        // line 126
        echo "</label>
          <select class=\"form-select\" name=\"chartValueColumnSelect\" id=\"chartValueColumnSelect\" disabled>
            ";
        // line 128
        $context["selected"] = false;
        // line 129
        echo "            ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["keys"] ?? null));
        foreach ($context['_seq'] as $context["idx"] => $context["key"]) {
            // line 130
            echo "              ";
            if (twig_get_attribute($this->env, $this->source, (($__internal_compile_4 = ($context["fields_meta"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4[$context["idx"]] ?? null) : null), "isNumericType", [], "method", false, false, false, 130)) {
                // line 131
                echo "                ";
                if ((( !($context["selected"] ?? null) && (0 !== twig_compare($context["idx"], ($context["xaxis"] ?? null)))) && (0 !== twig_compare($context["idx"], ($context["series_column"] ?? null))))) {
                    // line 132
                    echo "                  <option value=\"";
                    echo twig_escape_filter($this->env, $context["idx"], "html", null, true);
                    echo "\" selected>";
                    echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                    echo "</option>
                  ";
                    // line 133
                    $context["selected"] = true;
                    // line 134
                    echo "                ";
                } else {
                    // line 135
                    echo "                  <option value=\"";
                    echo twig_escape_filter($this->env, $context["idx"], "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                    echo "</option>
                ";
                }
                // line 137
                echo "              ";
            }
            // line 138
            echo "            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['idx'], $context['key'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 139
        echo "          </select>
        </div>

        ";
        // line 142
        echo twig_include($this->env, $context, "table/start_and_number_of_rows_fieldset.twig", ($context["start_and_number_of_rows_fieldset"] ?? null));
        echo "

        <div id=\"resizer\">
          <div class=\"position-absolute top-0 end-0 mt-1 me-1\">
            <a class=\"disableAjax\" id=\"saveChart\" href=\"#\" download=\"chart.png\">
              ";
        // line 147
        echo PhpMyAdmin\Html\Generator::getImage("b_saveimage", _gettext("Save chart as image"));
        echo "
            </a>
          </div>
          <div id=\"querychart\" dir=\"ltr\"></div>
        </div>
      </form>
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "table/chart/tbl_chart.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  390 => 147,  382 => 142,  377 => 139,  371 => 138,  368 => 137,  360 => 135,  357 => 134,  355 => 133,  348 => 132,  345 => 131,  342 => 130,  337 => 129,  335 => 128,  331 => 126,  324 => 122,  318 => 121,  316 => 120,  307 => 119,  303 => 118,  299 => 116,  292 => 112,  284 => 107,  280 => 106,  272 => 102,  269 => 101,  263 => 98,  256 => 96,  254 => 95,  250 => 94,  247 => 92,  240 => 90,  238 => 89,  234 => 88,  229 => 84,  223 => 83,  220 => 82,  212 => 80,  204 => 78,  201 => 77,  198 => 76,  194 => 75,  190 => 73,  183 => 69,  177 => 68,  169 => 66,  161 => 64,  158 => 63,  155 => 62,  152 => 61,  149 => 60,  145 => 59,  141 => 57,  137 => 56,  135 => 55,  129 => 51,  122 => 47,  113 => 41,  106 => 37,  99 => 33,  92 => 29,  85 => 25,  78 => 21,  71 => 17,  64 => 13,  58 => 10,  51 => 7,  47 => 6,  41 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "table/chart/tbl_chart.twig", "/var/www/html/public/phpmyadmin/templates/table/chart/tbl_chart.twig");
    }
}
