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

/* database/qbe/index.twig */
class __TwigTemplate_9894df4c6dbb7a3391d403d5ff6c47b4173b24fb3ee03fe793765b13a4f6529b extends Template
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
        echo "<ul class=\"nav nav-pills m-2\">
  <li class=\"nav-item\">
    <a class=\"nav-link\" href=\"";
        // line 3
        echo PhpMyAdmin\Url::getFromRoute("/database/multi-table-query", ($context["url_params"] ?? null));
        echo "\">
      ";
echo _gettext("Multi-table query");
        // line 5
        echo "    </a>
  </li>

  <li class=\"nav-item\">
    <a class=\"nav-link active\" href=\"";
        // line 9
        echo PhpMyAdmin\Url::getFromRoute("/database/qbe", ($context["url_params"] ?? null));
        echo "\">
      ";
echo _gettext("Query by example");
        // line 11
        echo "    </a>
  </li>
</ul>

";
        // line 15
        ob_start(function () { return ''; });
        // line 16
        echo "  ";
echo _gettext("Switch to %svisual builder%s");
        $___internal_parse_0_ = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 15
        echo call_user_func_array($this->env->getFilter('notice')->getCallable(), [twig_sprintf($___internal_parse_0_, (("<a href=\"" . PhpMyAdmin\Url::getFromRoute("/database/designer", twig_array_merge(($context["url_params"] ?? null), ["query" => true]))) . "\">"), "</a>")]);
        // line 18
        echo "
";
        // line 19
        if (($context["has_message_to_display"] ?? null)) {
            // line 20
            echo "  ";
            echo call_user_func_array($this->env->getFilter('error')->getCallable(), [_gettext("You have to choose at least one column to display!")]);
            echo "
";
        }
        // line 22
        echo "
";
        // line 23
        echo ($context["selection_form_html"] ?? null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "database/qbe/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 23,  82 => 22,  76 => 20,  74 => 19,  71 => 18,  69 => 15,  65 => 16,  63 => 15,  57 => 11,  52 => 9,  46 => 5,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/qbe/index.twig", "/var/www/html/public/phpmyadmin/templates/database/qbe/index.twig");
    }
}
