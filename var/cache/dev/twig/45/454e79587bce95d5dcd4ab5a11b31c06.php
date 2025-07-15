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

/* security/login.html.twig */
class __TwigTemplate_dbd4a89eb16812eef337ed2aad902575 extends Template
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
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "security/login.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "security/login.html.twig"));

        $this->parent = $this->load("base.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Iniciar Sesión - Sistema de Gestión de Voluntarios";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 6
        yield "<div class=\"min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 flex items-center justify-center p-4\">
    <div class=\"max-w-md w-full\">
        <div class=\"text-center mb-8\">
            <div class=\"inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4\">
                <i data-lucide=\"users\" class=\"w-8 h-8 text-blue-600\"></i>
            </div>
            <h1 class=\"text-3xl font-bold text-white mb-2\">Sistema de Gestión</h1>
            <p class=\"text-blue-200\">Gestión de Voluntarios</p>
        </div>

        <div class=\"bg-white rounded-2xl shadow-2xl p-8\">
            ";
        // line 18
        yield "            ";
        if ((($tmp = (isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 18, $this->source); })())) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 19
            yield "                <div class=\"flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-lg mb-4\">
                    <i data-lucide=\"alert-circle\" class=\"w-5 h-5 text-red-500 flex-shrink-0\"></i>
                    <span class=\"text-sm text-red-700\">";
            // line 21
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(CoreExtension::getAttribute($this->env, $this->source, (isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 21, $this->source); })()), "messageKey", [], "any", false, false, false, 21), CoreExtension::getAttribute($this->env, $this->source, (isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 21, $this->source); })()), "messageData", [], "any", false, false, false, 21), "security"), "html", null, true);
            yield "</span>
                </div>
            ";
        }
        // line 24
        yield "
            <form method=\"post\" action=\"";
        // line 25
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_login");
        yield "\">
                <div class=\"space-y-6\">
                    <div>
                        <label for=\"inputEmail\" class=\"block text-sm font-medium text-gray-700 mb-2\">
                            Correo electrónico
                        </label>
                        <div class=\"relative\">
                            <i data-lucide=\"mail\" class=\"absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400\"></i>
                            <input type=\"email\" value=\"";
        // line 33
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["last_username"]) || array_key_exists("last_username", $context) ? $context["last_username"] : (function () { throw new RuntimeError('Variable "last_username" does not exist.', 33, $this->source); })()), "html", null, true);
        yield "\" name=\"_username\" id=\"inputEmail\"
                                   class=\"w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors\"
                                   placeholder=\"admin@voluntarios.org\" autocomplete=\"email\" required autofocus>
                        </div>
                    </div>

                    <div>
                        <label for=\"inputPassword\" class=\"block text-sm font-medium text-gray-700 mb-2\">
                            Contraseña
                        </label>
                        <div class=\"relative\">
                            <i data-lucide=\"lock\" class=\"absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400\"></i>
                            <input type=\"password\" name=\"_password\" id=\"inputPassword\"
                                   class=\"w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors\"
                                   placeholder=\"••••••••\" autocomplete=\"current-password\" required>
                        </div>
                    </div>

                    <input type=\"hidden\" name=\"_csrf_token\" value=\"";
        // line 51
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken("authenticate"), "html", null, true);
        yield "\">

                    <button class=\"w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium py-3 px-4 rounded-lg transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2\" type=\"submit\">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div class=\"mt-6 p-4 bg-blue-50 rounded-lg\">
                <p class=\"text-sm text-blue-800 text-center\">
                    <strong>Demo:</strong> admin@voluntarios.org / admin123
                </p>
            </div>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "security/login.html.twig";
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
        return array (  161 => 51,  140 => 33,  129 => 25,  126 => 24,  120 => 21,  116 => 19,  113 => 18,  100 => 6,  87 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Iniciar Sesión - Sistema de Gestión de Voluntarios{% endblock %}

{% block body %}
<div class=\"min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 flex items-center justify-center p-4\">
    <div class=\"max-w-md w-full\">
        <div class=\"text-center mb-8\">
            <div class=\"inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4\">
                <i data-lucide=\"users\" class=\"w-8 h-8 text-blue-600\"></i>
            </div>
            <h1 class=\"text-3xl font-bold text-white mb-2\">Sistema de Gestión</h1>
            <p class=\"text-blue-200\">Gestión de Voluntarios</p>
        </div>

        <div class=\"bg-white rounded-2xl shadow-2xl p-8\">
            {# Muestra el error si existe #}
            {% if error %}
                <div class=\"flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-lg mb-4\">
                    <i data-lucide=\"alert-circle\" class=\"w-5 h-5 text-red-500 flex-shrink-0\"></i>
                    <span class=\"text-sm text-red-700\">{{ error.messageKey|trans(error.messageData, 'security') }}</span>
                </div>
            {% endif %}

            <form method=\"post\" action=\"{{ path('app_login') }}\">
                <div class=\"space-y-6\">
                    <div>
                        <label for=\"inputEmail\" class=\"block text-sm font-medium text-gray-700 mb-2\">
                            Correo electrónico
                        </label>
                        <div class=\"relative\">
                            <i data-lucide=\"mail\" class=\"absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400\"></i>
                            <input type=\"email\" value=\"{{ last_username }}\" name=\"_username\" id=\"inputEmail\"
                                   class=\"w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors\"
                                   placeholder=\"admin@voluntarios.org\" autocomplete=\"email\" required autofocus>
                        </div>
                    </div>

                    <div>
                        <label for=\"inputPassword\" class=\"block text-sm font-medium text-gray-700 mb-2\">
                            Contraseña
                        </label>
                        <div class=\"relative\">
                            <i data-lucide=\"lock\" class=\"absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400\"></i>
                            <input type=\"password\" name=\"_password\" id=\"inputPassword\"
                                   class=\"w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors\"
                                   placeholder=\"••••••••\" autocomplete=\"current-password\" required>
                        </div>
                    </div>

                    <input type=\"hidden\" name=\"_csrf_token\" value=\"{{ csrf_token('authenticate') }}\">

                    <button class=\"w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium py-3 px-4 rounded-lg transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2\" type=\"submit\">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div class=\"mt-6 p-4 bg-blue-50 rounded-lg\">
                <p class=\"text-sm text-blue-800 text-center\">
                    <strong>Demo:</strong> admin@voluntarios.org / admin123
                </p>
            </div>
        </div>
    </div>
</div>
{% endblock %}", "security/login.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\security\\login.html.twig");
    }
}
