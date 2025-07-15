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

/* layout/app.html.twig */
class __TwigTemplate_a48f5d4df87ac91d793a2f83fafa476a extends Template
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

        $this->blocks = [
            'body' => [$this, 'block_body'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->load("base.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 4
        yield "<div class=\"flex h-screen bg-gray-50\">
    <!-- Sidebar -->
    ";
        // line 6
        yield from $this->load("layout/sidebar.html.twig", 6)->unwrap()->yield($context);
        // line 7
        yield "    
    <!-- Main content -->
    <div class=\"flex-1 flex flex-col overflow-hidden\">
        <!-- Header -->
        ";
        // line 11
        yield from $this->load("layout/header.html.twig", 11)->unwrap()->yield($context);
        // line 12
        yield "        
        <!-- Main content area -->
        <main class=\"flex-1 overflow-y-auto\">
            ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "flashes", ["success"], "method", false, false, false, 15));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 16
            yield "                <div class=\"mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg\">
                    <div class=\"flex items-center\">
                        <i data-lucide=\"check-circle\" class=\"w-5 h-5 text-green-500 mr-2\"></i>
                        <span class=\"text-green-800\">";
            // line 19
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
                    </div>
                </div>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        yield "            
            ";
        // line 24
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "flashes", ["error"], "method", false, false, false, 24));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 25
            yield "                <div class=\"mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg\">
                    <div class=\"flex items-center\">
                        <i data-lucide=\"alert-circle\" class=\"w-5 h-5 text-red-500 mr-2\"></i>
                        <span class=\"text-red-800\">";
            // line 28
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
                    </div>
                </div>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        yield "            
            ";
        // line 33
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 34
        yield "        </main>
    </div>
</div>
";
        yield from [];
    }

    // line 33
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layout/app.html.twig";
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
        return array (  132 => 33,  124 => 34,  122 => 33,  119 => 32,  109 => 28,  104 => 25,  100 => 24,  97 => 23,  87 => 19,  82 => 16,  78 => 15,  73 => 12,  71 => 11,  65 => 7,  63 => 6,  59 => 4,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "layout/app.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\layout\\app.html.twig");
    }
}
