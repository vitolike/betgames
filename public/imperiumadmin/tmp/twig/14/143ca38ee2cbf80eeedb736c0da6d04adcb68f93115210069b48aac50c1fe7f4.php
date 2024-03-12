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

/* database/events/editor_form.twig */
class __TwigTemplate_a59b5b910578ac01b0a258d1f127eb09e6ee9e56f292a1dfd48dc6ddb45ba31f extends Template
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
        echo "<form class=\"rte_form\" action=\"";
        echo PhpMyAdmin\Url::getFromRoute("/database/events");
        echo "\" method=\"post\">
  ";
        // line 2
        echo PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null));
        echo "
  <input name=\"";
        // line 3
        echo twig_escape_filter($this->env, ($context["mode"] ?? null), "html", null, true);
        echo "_item\" type=\"hidden\" value=\"1\">
  ";
        // line 4
        if ((0 === twig_compare(($context["mode"] ?? null), "edit"))) {
            // line 5
            echo "    <input name=\"item_original_name\" type=\"hidden\" value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_original_name", [], "any", false, false, false, 5), "html", null, true);
            echo "\">
  ";
        }
        // line 7
        echo "
  <div class=\"card\">
    <div class=\"card-header\">
      ";
echo _gettext("Details");
        // line 11
        echo "      ";
        if ((0 !== twig_compare(($context["mode"] ?? null), "edit"))) {
            // line 12
            echo "        ";
            echo PhpMyAdmin\Html\MySQLDocumentation::show("CREATE_EVENT");
            echo "
      ";
        }
        // line 14
        echo "    </div>

    <div class=\"card-body\">
      <table class=\"rte_table table table-borderless table-sm\">
        <tr>
          <td>";
echo _gettext("Event name");
        // line 19
        echo "</td>
          <td>
            <input type=\"text\" name=\"item_name\" value=\"";
        // line 21
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_name", [], "any", false, false, false, 21), "html", null, true);
        echo "\" maxlength=\"64\">
          </td>
        </tr>
        <tr>
          <td>";
echo _gettext("Status");
        // line 25
        echo "</td>
          <td>
            <select name=\"item_status\">
              ";
        // line 28
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["status_display"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["status"]) {
            // line 29
            echo "                <option value=\"";
            echo twig_escape_filter($this->env, $context["status"], "html", null, true);
            echo "\"";
            echo (((0 === twig_compare($context["status"], twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_status", [], "any", false, false, false, 29)))) ? (" selected") : (""));
            echo ">";
            echo twig_escape_filter($this->env, $context["status"], "html", null, true);
            echo "</option>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['status'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "            </select>
          </td>
        </tr>
        <tr>
          <td>";
echo _gettext("Event type");
        // line 35
        echo "</td>
          <td>
            ";
        // line 37
        if (($context["is_ajax"] ?? null)) {
            // line 38
            echo "              <select name=\"item_type\">
                ";
            // line 39
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["event_type"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
                // line 40
                echo "                  <option value=\"";
                echo twig_escape_filter($this->env, $context["type"], "html", null, true);
                echo "\"";
                echo (((0 === twig_compare($context["type"], twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_type", [], "any", false, false, false, 40)))) ? (" selected") : (""));
                echo ">";
                echo twig_escape_filter($this->env, $context["type"], "html", null, true);
                echo "</option>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['type'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 42
            echo "              </select>
            ";
        } else {
            // line 44
            echo "              <input name=\"item_type\" type=\"hidden\" value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_type", [], "any", false, false, false, 44), "html", null, true);
            echo "\">
              <div class=\"fw-bold text-center w-50\">
                ";
            // line 46
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_type", [], "any", false, false, false, 46), "html", null, true);
            echo "
              </div>
              <input type=\"submit\" name=\"item_changetype\" class=\"w-50\" value=\"";
            // line 48
            echo twig_escape_filter($this->env, twig_sprintf(_gettext("Change to %s"), twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_type_toggle", [], "any", false, false, false, 48)), "html", null, true);
            echo "\">
            ";
        }
        // line 50
        echo "          </td>
        </tr>
        <tr class=\"onetime_event_row";
        // line 52
        echo (((0 !== twig_compare(twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_type", [], "any", false, false, false, 52), "ONE TIME"))) ? (" hide") : (""));
        echo "\">
          <td>";
echo _gettext("Execute at");
        // line 53
        echo "</td>
          <td class=\"text-nowrap\">
            <input type=\"text\" name=\"item_execute_at\" value=\"";
        // line 55
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_execute_at", [], "any", false, false, false, 55), "html", null, true);
        echo "\" class=\"datetimefield\">
          </td>
        </tr>
        <tr class=\"recurring_event_row";
        // line 58
        echo (((0 !== twig_compare(twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_type", [], "any", false, false, false, 58), "RECURRING"))) ? (" hide") : (""));
        echo "\">
          <td>";
echo _gettext("Execute every");
        // line 59
        echo "</td>
          <td>
            <input class=\"w-50\" type=\"text\" name=\"item_interval_value\" value=\"";
        // line 61
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_interval_value", [], "any", false, false, false, 61), "html", null, true);
        echo "\">
            <select class=\"w-50\" name=\"item_interval_field\">
              ";
        // line 63
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["event_interval"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["interval"]) {
            // line 64
            echo "                <option value=\"";
            echo twig_escape_filter($this->env, $context["interval"], "html", null, true);
            echo "\"";
            echo (((0 === twig_compare($context["interval"], twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_interval_field", [], "any", false, false, false, 64)))) ? (" selected") : (""));
            echo ">";
            echo twig_escape_filter($this->env, $context["interval"], "html", null, true);
            echo "</option>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interval'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 66
        echo "            </select>
          </td>
        </tr>
        <tr class=\"recurring_event_row";
        // line 69
        echo (((0 !== twig_compare(twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_type", [], "any", false, false, false, 69), "RECURRING"))) ? (" hide") : (""));
        echo "\">
          <td>";
echo _pgettext("Start of recurring event", "Start");
        // line 70
        echo "</td>
          <td class=\"text-nowrap\">
            <input type=\"text\" name=\"item_starts\" value=\"";
        // line 72
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_starts", [], "any", false, false, false, 72), "html", null, true);
        echo "\" class=\"datetimefield\">
          </td>
        </tr>
        <tr class=\"recurring_event_row";
        // line 75
        echo (((0 !== twig_compare(twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_type", [], "any", false, false, false, 75), "RECURRING"))) ? (" hide") : (""));
        echo "\">
          <td>";
echo _pgettext("End of recurring event", "End");
        // line 76
        echo "</td>
          <td class=\"text-nowrap\">
            <input type=\"text\" name=\"item_ends\" value=\"";
        // line 78
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_ends", [], "any", false, false, false, 78), "html", null, true);
        echo "\" class=\"datetimefield\">
          </td>
        </tr>
        <tr>
          <td>";
echo _gettext("Definition");
        // line 82
        echo "</td>
          <td>
            <textarea name=\"item_definition\" rows=\"15\" cols=\"40\">";
        // line 85
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_definition", [], "any", false, false, false, 85), "html", null, true);
        // line 86
        echo "</textarea>
          </td>
        </tr>
        <tr>
          <td>";
echo _gettext("On completion preserve");
        // line 90
        echo "</td>
          <td>
            <input type=\"checkbox\" name=\"item_preserve\"";
        // line 92
        echo twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_preserve", [], "any", false, false, false, 92);
        echo ">
          </td>
        </tr>
        <tr>
          <td>";
echo _gettext("Definer");
        // line 96
        echo "</td>
          <td>
            <input type=\"text\" name=\"item_definer\" value=\"";
        // line 98
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_definer", [], "any", false, false, false, 98), "html", null, true);
        echo "\">
          </td>
        </tr>
        <tr>
          <td>";
echo _gettext("Comment");
        // line 102
        echo "</td>
          <td>
            <input type=\"text\" name=\"item_comment\" value=\"";
        // line 104
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["event"] ?? null), "item_comment", [], "any", false, false, false, 104), "html", null, true);
        echo "\" maxlength=\"64\">
          </td>
        </tr>
      </table>
    </div>

    ";
        // line 110
        if (($context["is_ajax"] ?? null)) {
            // line 111
            echo "      <input type=\"hidden\" name=\"editor_process_";
            echo twig_escape_filter($this->env, ($context["mode"] ?? null), "html", null, true);
            echo "\" value=\"true\">
      <input type=\"hidden\" name=\"ajax_request\" value=\"true\">
    ";
        } else {
            // line 114
            echo "      <div class=\"card-footer\">
        <input class=\"btn btn-primary\" type=\"submit\" name=\"editor_process_";
            // line 115
            echo twig_escape_filter($this->env, ($context["mode"] ?? null), "html", null, true);
            echo "\" value=\"";
echo _gettext("Go");
            echo "\">
      </div>
    ";
        }
        // line 118
        echo "  </div>
</form>
";
    }

    public function getTemplateName()
    {
        return "database/events/editor_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  325 => 118,  317 => 115,  314 => 114,  307 => 111,  305 => 110,  296 => 104,  292 => 102,  284 => 98,  280 => 96,  272 => 92,  268 => 90,  261 => 86,  259 => 85,  255 => 82,  247 => 78,  243 => 76,  238 => 75,  232 => 72,  228 => 70,  223 => 69,  218 => 66,  205 => 64,  201 => 63,  196 => 61,  192 => 59,  187 => 58,  181 => 55,  177 => 53,  172 => 52,  168 => 50,  163 => 48,  158 => 46,  152 => 44,  148 => 42,  135 => 40,  131 => 39,  128 => 38,  126 => 37,  122 => 35,  115 => 31,  102 => 29,  98 => 28,  93 => 25,  85 => 21,  81 => 19,  73 => 14,  67 => 12,  64 => 11,  58 => 7,  52 => 5,  50 => 4,  46 => 3,  42 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/events/editor_form.twig", "/var/www/html/public/phpmyadmin/templates/database/events/editor_form.twig");
    }
}
