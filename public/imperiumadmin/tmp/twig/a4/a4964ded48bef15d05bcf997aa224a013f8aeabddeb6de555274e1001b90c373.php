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

/* modals/build_query.twig */
class __TwigTemplate_7b026e2b4d524001e252d065307878bc3e73add4cfd02ac5db3f9a774bb24dc1 extends Template
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
        echo "<div class=\"modal fade\" id=\"buildQueryModal\" tabindex=\"-1\" aria-labelledby=\"buildQueryModalLabel\" aria-hidden=\"true\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h5 class=\"modal-title\" id=\"buildQueryModalLabel\">";
echo _gettext("Loading");
        // line 5
        echo "</h5>
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"";
echo _gettext("Close");
        // line 6
        echo "\"></button>
      </div>
      <div id=\"box\" class=\"modal-body\">
        <form method=\"post\" action=\"";
        // line 9
        echo PhpMyAdmin\Url::getFromRoute("/database/qbe");
        echo "\" id=\"vqb_form\">
          <textarea cols=\"80\" name=\"sql_query\" id=\"textSqlquery\" rows=\"15\"></textarea>
          <input type=\"hidden\" name=\"submit_sql\" value=\"true\">
          ";
        // line 12
        echo PhpMyAdmin\Url::getHiddenInputs(($context["get_db"] ?? null));
        echo "
        </form>
      </div>
      <div class=\"modal-footer\">
        <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">";
echo _gettext("Close");
        // line 16
        echo "</button>
        <button type=\"button\" class=\"btn btn-secondary\" id=\"buildQuerySubmitButton\">";
echo _gettext("Submit");
        // line 17
        echo "</button>
      </div>
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "modals/build_query.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 17,  67 => 16,  59 => 12,  53 => 9,  48 => 6,  44 => 5,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modals/build_query.twig", "/var/www/html/public/phpmyadmin/templates/modals/build_query.twig");
    }
}
