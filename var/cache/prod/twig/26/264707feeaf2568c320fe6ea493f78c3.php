<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* layout/header.html.twig */
class __TwigTemplate_7f7ece0e2a8d011b474b0e6d3e739889 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'page_title' => [$this, 'block_page_title'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<header class=\"bg-white shadow-sm border-b border-gray-200 px-6 py-4\">
    <div class=\"flex items-center justify-between\">
        <div>
            <h1 class=\"text-2xl font-bold text-gray-900\">";
        // line 4
        yield from $this->unwrap()->yieldBlock('page_title', $context, $blocks);
        yield "</h1>
            <p class=\"text-sm text-gray-500 mt-1\">
                ";
        // line 6
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "l, d \\d\\e F \\d\\e Y"), "html", null, true);
        yield "
            </p>
        </div>

        <div class=\"flex items-center gap-4\">
            <!-- Search -->
            <div class=\"relative hidden md:block\">
                <i data-lucide=\"search\" class=\"absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400\"></i>
                <input
                    type=\"text\"
                    placeholder=\"Buscar...\"
                    class=\"pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64\"
                />
            </div>

            <!-- Notifications -->
            <button class=\"relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors\">
                <i data-lucide=\"bell\" class=\"w-5 h-5\"></i>
                <span class=\"absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full\"></span>
            </button>

            <!-- User menu -->
            <div class=\"flex items-center gap-3\">
                <div class=\"text-right hidden sm:block\">
                    ";
        // line 31
        yield "                    <p class=\"text-xs text-gray-500\">";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "user", [], "any", false, false, false, 31), "email", [], "any", false, false, false, 31), "html", null, true);
        yield "</p>
                </div>
                <div class=\"w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center\">
                    <i data-lucide=\"user\" class=\"w-4 h-4 text-white\"></i>
                </div>
            </div>
        </div>
    </div>
</header>";
        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Dashboard";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layout/header.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  95 => 4,  80 => 31,  53 => 6,  48 => 4,  43 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "layout/header.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\layout\\header.html.twig");
    }
}
