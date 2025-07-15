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

/* volunteer/edit_volunteer.html.twig */
class __TwigTemplate_efd429cc7c4ab3fb86f6ab712091f783 extends Template
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
            'page_title' => [$this, 'block_page_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 3
        return "layout/app.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "volunteer/edit_volunteer.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "volunteer/edit_volunteer.html.twig"));

        $this->parent = $this->load("layout/app.html.twig", 3);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "page_title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "page_title"));

        yield "Editar Voluntario";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 7
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        // line 8
        yield "
    <div class=\"container mx-auto p-6\">
        <h1 class=\"text-2xl font-bold mb-6 text-gray-900\">Editar Voluntario</h1>

        ";
        // line 13
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 13, $this->source); })()), "flashes", ["success"], "method", false, false, false, 13));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 14
            yield "            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">";
            // line 15
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 18
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 18, $this->source); })()), "flashes", ["error"], "method", false, false, false, 18));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 19
            yield "            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">";
            // line 20
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        yield "
        ";
        // line 25
        yield "        ";
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 25, $this->source); })()), 'form_start', ["attr" => ["class" => "bg-white p-6 rounded-lg shadow-md"], "enctype" => "multipart/form-data"]);
        yield "

        ";
        // line 28
        yield "        <div class=\"flex flex-col md:flex-row gap-6 mb-8\">

            ";
        // line 31
        yield "            <div class=\"w-full md:w-1/2 p-4 border border-gray-200 rounded-lg text-center bg-gray-50\">
                <h3 class=\"text-xl font-semibold mb-4 text-gray-800\">Foto de Perfil</h3>
                <div class=\"mb-4\">
                    ";
        // line 34
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["volunteer"]) || array_key_exists("volunteer", $context) ? $context["volunteer"] : (function () { throw new RuntimeError('Variable "volunteer" does not exist.', 34, $this->source); })()), "profilePicture", [], "any", false, false, false, 34)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 35
            yield "                        <img id=\"preview-element\" src=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl(("uploads/profile_pictures/" . CoreExtension::getAttribute($this->env, $this->source, (isset($context["volunteer"]) || array_key_exists("volunteer", $context) ? $context["volunteer"] : (function () { throw new RuntimeError('Variable "volunteer" does not exist.', 35, $this->source); })()), "profilePicture", [], "any", false, false, false, 35))), "html", null, true);
            yield "\"
                             alt=\"Foto de perfil\"
                             class=\"w-32 h-32 rounded-full object-cover mx-auto border-2 border-blue-300 shadow mb-4\">
                    ";
        } else {
            // line 39
            yield "                        ";
            // line 40
            yield "                        <div id=\"preview-element\"
                             class=\"w-32 h-32 rounded-full mx-auto bg-gray-300 flex items-center justify-center text-gray-600 text-sm mb-4 border-2 border-blue-300 shadow\">
                            Sin Foto
                        </div>
                    ";
        }
        // line 45
        yield "                </div>
                ";
        // line 47
        yield "                <div class=\"w-fit mx-auto\">
                    ";
        // line 48
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 48, $this->source); })()), "profilePicture", [], "any", false, false, false, 48), 'row', ["label" => "Cambiar foto", "attr" => ["class" => "block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"]]);
        // line 53
        yield "
                </div>
                <p class=\"text-xs text-gray-500 mt-1\">Sube una imagen JPG o PNG (máx. 1MB).</p>
            </div>

            ";
        // line 59
        yield "            <div class=\"w-full md:w-1/2 p-4 border border-gray-200 rounded-lg bg-white\">
                <h3 class=\"text-xl font-semibold mb-4 text-gray-800\">Datos Básicos</h3>
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                    <div>";
        // line 62
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 62, $this->source); })()), "name", [], "any", false, false, false, 62), 'row');
        yield "</div>
                    <div>";
        // line 63
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 63, $this->source); })()), "lastName", [], "any", false, false, false, 63), 'row');
        yield "</div>
                    <div>";
        // line 64
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 64, $this->source); })()), "dni", [], "any", false, false, false, 64), 'row');
        yield "</div>
                    <div>";
        // line 65
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 65, $this->source); })()), "user", [], "any", false, false, false, 65), "email", [], "any", false, false, false, 65), 'row');
        yield "</div>
                    <div>";
        // line 66
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 66, $this->source); })()), "phone", [], "any", false, false, false, 66), 'row');
        yield "</div>
                    <div>";
        // line 67
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 67, $this->source); })()), "dateOfBirth", [], "any", false, false, false, 67), 'row');
        yield "</div>
                </div>
            </div>
        </div>

        ";
        // line 73
        yield "        <div class=\"w-full p-4 border border-gray-200 rounded-lg bg-white mb-8\">
            <h3 class=\"text-xl font-semibold mb-4 text-gray-800\">Datos Detallados del Voluntario</h3>

            ";
        // line 77
        yield "            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Dirección</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 79
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 79, $this->source); })()), "streetType", [], "any", false, false, false, 79), 'row');
        yield "</div>
                <div>";
        // line 80
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 80, $this->source); })()), "address", [], "any", false, false, false, 80), 'row');
        yield "</div>
                <div>";
        // line 81
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 81, $this->source); })()), "postalCode", [], "any", false, false, false, 81), 'row');
        yield "</div>
                <div>";
        // line 82
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 82, $this->source); })()), "province", [], "any", false, false, false, 82), 'row');
        yield "</div>
                <div>";
        // line 83
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 83, $this->source); })()), "city", [], "any", false, false, false, 83), 'row');
        yield "</div>
            </div>

            ";
        // line 87
        yield "            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Información de Emergencia</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 89
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 89, $this->source); })()), "contactPerson1", [], "any", false, false, false, 89), 'row');
        yield "</div>
                <div>";
        // line 90
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 90, $this->source); })()), "contactPhone1", [], "any", false, false, false, 90), 'row');
        yield "</div>
                <div>";
        // line 91
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 91, $this->source); })()), "contactPerson2", [], "any", false, false, false, 91), 'row');
        yield "</div>
                <div>";
        // line 92
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 92, $this->source); })()), "contactPhone2", [], "any", false, false, false, 92), 'row');
        yield "</div>
            </div>

            ";
        // line 96
        yield "            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Información Médica</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 98
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 98, $this->source); })()), "allergies", [], "any", false, false, false, 98), 'row');
        yield "</div>
                ";
        // line 100
        yield "            </div>

            ";
        // line 103
        yield "            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Profesional y Cualificaciones</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 105
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 105, $this->source); })()), "profession", [], "any", false, false, false, 105), 'row');
        yield "</div>
                <div>";
        // line 106
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 106, $this->source); })()), "employmentStatus", [], "any", false, false, false, 106), 'row');
        yield "</div>
                <div>";
        // line 107
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 107, $this->source); })()), "drivingLicenses", [], "any", false, false, false, 107), 'row');
        yield "</div>
                <div>";
        // line 108
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 108, $this->source); })()), "drivingLicenseExpiryDate", [], "any", false, false, false, 108), 'row');
        yield "</div>
            </div>
            <div class=\"mb-4\">
                ";
        // line 111
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 111, $this->source); })()), "navigationLicenses", [], "any", false, false, false, 111), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 114
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 114, $this->source); })()), "specificQualifications", [], "any", false, false, false, 114), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 117
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 117, $this->source); })()), "otherQualifications", [], "any", false, false, false, 117), 'row');
        yield "
            </div>

            ";
        // line 121
        yield "            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Otros Datos e Intereses</h2>
            <div class=\"mb-4\">
                ";
        // line 123
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 123, $this->source); })()), "languages", [], "any", false, false, false, 123), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 126
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 126, $this->source); })()), "motivation", [], "any", false, false, false, 126), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 129
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 129, $this->source); })()), "howKnown", [], "any", false, false, false, 129), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 132
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 132, $this->source); })()), "hasVolunteeredBefore", [], "any", false, false, false, 132), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 135
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 135, $this->source); })()), "previousVolunteeringInstitutions", [], "any", false, false, false, 135), 'row');
        yield "
            </div>

            ";
        // line 139
        yield "            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Rol y Especialización</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 141
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 141, $this->source); })()), "role", [], "any", false, false, false, 141), 'row');
        yield "</div>
                <div>";
        // line 142
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 142, $this->source); })()), "specialization", [], "any", false, false, false, 142), 'row');
        yield "</div>
                
            </div>

            ";
        // line 147
        yield "            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Actualizar Contraseña</h2>
            <p class=\"text-sm text-gray-600 mb-4\">Solo cambia la contraseña si deseas actualizarla. Déjala en blanco para mantener la actual.</p>
           ";
        // line 150
        yield "        </div>

        <div class=\"flex items-center justify-end gap-3 mt-6\">
            <a href=\"";
        // line 153
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_list");
        yield "\" class=\"px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors\">Cancelar</a>
            <button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors\">Guardar Cambios</button>
        </div>

        ";
        // line 157
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 157, $this->source); })()), 'form_end');
        yield "
    </div>

    ";
        // line 161
        yield "    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Selecciona el input de archivo por su tipo y parte del atributo 'name'
            const profilePictureInput = document.querySelector('input[type=\"file\"][name\$=\"[profilePicture]\"]');
            // Usamos 'let' para la previsualización porque su referencia podría cambiar (de div a img)
            let previewElement = document.getElementById('preview-element');

            if (profilePictureInput && previewElement) {
                profilePictureInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            // Si el elemento de previsualización actual es un DIV (el \"Sin Foto\"),
                            // lo reemplazamos por una nueva etiqueta IMG.
                            if (previewElement.tagName === 'DIV') {
                                const newImg = document.createElement('img');
                                newImg.id = 'preview-element'; // Mantiene el mismo ID
                                newImg.alt = 'Foto de perfil';
                                // Copia las clases para mantener el estilo circular y responsivo
                                newImg.className = 'w-32 h-32 rounded-full object-cover mx-auto border-2 border-blue-300 shadow mb-4';
                                previewElement.parentNode.replaceChild(newImg, previewElement);
                                previewElement = newImg; // Actualiza la referencia al nuevo elemento IMG
                            }
                            // Establece la fuente de la imagen
                            previewElement.src = e.target.result;
                        };
                        reader.onerror = function(e) {
                            console.error('Error al leer el archivo:', e);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Si no se selecciona ningún archivo o se cancela,
                        // y el elemento actual es una IMG, lo revertimos a un DIV \"Sin Foto\".
                        if (previewElement.tagName === 'IMG') {
                            const newDiv = document.createElement('div');
                            newDiv.id = 'preview-element'; // Mantiene el mismo ID
                            newDiv.className = 'w-32 h-32 rounded-full mx-auto bg-gray-300 flex items-center justify-center text-gray-600 text-sm mb-4 border-2 border-blue-300 shadow';
                            newDiv.textContent = 'Sin Foto';
                            previewElement.parentNode.replaceChild(newDiv, previewElement);
                            previewElement = newDiv; // Actualiza la referencia al nuevo elemento DIV
                        }
                    }
                });
            } else {
                console.error(\"ERROR: Elementos de previsualización o input de archivo no encontrados.\");
            }
        });
    </script>
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
        return "volunteer/edit_volunteer.html.twig";
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
        return array (  401 => 161,  395 => 157,  388 => 153,  383 => 150,  379 => 147,  372 => 142,  368 => 141,  364 => 139,  358 => 135,  352 => 132,  346 => 129,  340 => 126,  334 => 123,  330 => 121,  324 => 117,  318 => 114,  312 => 111,  306 => 108,  302 => 107,  298 => 106,  294 => 105,  290 => 103,  286 => 100,  282 => 98,  278 => 96,  272 => 92,  268 => 91,  264 => 90,  260 => 89,  256 => 87,  250 => 83,  246 => 82,  242 => 81,  238 => 80,  234 => 79,  230 => 77,  225 => 73,  217 => 67,  213 => 66,  209 => 65,  205 => 64,  201 => 63,  197 => 62,  192 => 59,  185 => 53,  183 => 48,  180 => 47,  177 => 45,  170 => 40,  168 => 39,  160 => 35,  158 => 34,  153 => 31,  149 => 28,  143 => 25,  140 => 23,  131 => 20,  128 => 19,  123 => 18,  114 => 15,  111 => 14,  106 => 13,  100 => 8,  87 => 7,  64 => 5,  41 => 3,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# templates/volunteer/edit_volunteer.html.twig #}

{% extends 'layout/app.html.twig' %} {# Usamos 'layout/app.html.twig' como base, ajusta si tu layout base es diferente #}

{% block page_title %}Editar Voluntario{% endblock %}

{% block content %}

    <div class=\"container mx-auto p-6\">
        <h1 class=\"text-2xl font-bold mb-6 text-gray-900\">Editar Voluntario</h1>

        {# Mensajes flash #}
        {% for message in app.flashes('success') %}
            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">{{ message }}</span>
            </div>
        {% endfor %}
        {% for message in app.flashes('error') %}
            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">{{ message }}</span>
            </div>
        {% endfor %}

        {# Contenedor principal del formulario con estilos #}
        {{ form_start(form, {'attr': {'class': 'bg-white p-6 rounded-lg shadow-md'}, 'enctype': 'multipart/form-data'}) }}

        {# CONTENEDOR PRINCIPAL: Flex para la fila superior (foto + datos básicos) #}
        <div class=\"flex flex-col md:flex-row gap-6 mb-8\">

            {# Recuadro de la foto de perfil (Izquierda - md:w-1/2) #}
            <div class=\"w-full md:w-1/2 p-4 border border-gray-200 rounded-lg text-center bg-gray-50\">
                <h3 class=\"text-xl font-semibold mb-4 text-gray-800\">Foto de Perfil</h3>
                <div class=\"mb-4\">
                    {% if volunteer.profilePicture %}
                        <img id=\"preview-element\" src=\"{{ asset('uploads/profile_pictures/' ~ volunteer.profilePicture) }}\"
                             alt=\"Foto de perfil\"
                             class=\"w-32 h-32 rounded-full object-cover mx-auto border-2 border-blue-300 shadow mb-4\">
                    {% else %}
                        {# ESTE ES EL DIV \"SIN FOTO\" VISIBLE INICIALMENTE #}
                        <div id=\"preview-element\"
                             class=\"w-32 h-32 rounded-full mx-auto bg-gray-300 flex items-center justify-center text-gray-600 text-sm mb-4 border-2 border-blue-300 shadow\">
                            Sin Foto
                        </div>
                    {% endif %}
                </div>
                {# El recuadro del input de archivo se mantiene estrecho y centrado #}
                <div class=\"w-fit mx-auto\">
                    {{ form_row(form.profilePicture, {
                        label: 'Cambiar foto',
                        attr: {
                            'class': 'block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none'
                        }
                    }) }}
                </div>
                <p class=\"text-xs text-gray-500 mt-1\">Sube una imagen JPG o PNG (máx. 1MB).</p>
            </div>

            {# Recuadro de Datos Básicos (Derecha - md:w-1/2) #}
            <div class=\"w-full md:w-1/2 p-4 border border-gray-200 rounded-lg bg-white\">
                <h3 class=\"text-xl font-semibold mb-4 text-gray-800\">Datos Básicos</h3>
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                    <div>{{ form_row(form.name) }}</div>
                    <div>{{ form_row(form.lastName) }}</div>
                    <div>{{ form_row(form.dni) }}</div>
                    <div>{{ form_row(form.user.email) }}</div>
                    <div>{{ form_row(form.phone) }}</div>
                    <div>{{ form_row(form.dateOfBirth) }}</div>
                </div>
            </div>
        </div>

        {# Recuadro con el Resto de Datos del Voluntario (debajo de la fila superior, ocupa todo el ancho) #}
        <div class=\"w-full p-4 border border-gray-200 rounded-lg bg-white mb-8\">
            <h3 class=\"text-xl font-semibold mb-4 text-gray-800\">Datos Detallados del Voluntario</h3>

            {# Sección: Dirección #}
            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Dirección</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.streetType) }}</div>
                <div>{{ form_row(form.address) }}</div>
                <div>{{ form_row(form.postalCode) }}</div>
                <div>{{ form_row(form.province) }}</div>
                <div>{{ form_row(form.city) }}</div>
            </div>

            {# Sección: Información de Emergencia #}
            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Información de Emergencia</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.contactPerson1) }}</div>
                <div>{{ form_row(form.contactPhone1) }}</div>
                <div>{{ form_row(form.contactPerson2) }}</div>
                <div>{{ form_row(form.contactPhone2) }}</div>
            </div>

            {# Sección: Información Médica #}
            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Información Médica</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.allergies) }}</div>
                {# <div>{{ form_row(form.medicalConditions) }}</div> #}
            </div>

            {# Sección: Información Profesional y Cualificaciones #}
            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Profesional y Cualificaciones</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.profession) }}</div>
                <div>{{ form_row(form.employmentStatus) }}</div>
                <div>{{ form_row(form.drivingLicenses) }}</div>
                <div>{{ form_row(form.drivingLicenseExpiryDate) }}</div>
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.navigationLicenses) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.specificQualifications) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.otherQualifications) }}
            </div>

            {# Sección: Otros Datos e Intereses #}
            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Otros Datos e Intereses</h2>
            <div class=\"mb-4\">
                {{ form_row(form.languages) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.motivation) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.howKnown) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.hasVolunteeredBefore) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.previousVolunteeringInstitutions) }}
            </div>

            {# Sección: Rol y Especialización #}
            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Rol y Especialización</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.role) }}</div>
                <div>{{ form_row(form.specialization) }}</div>
                
            </div>

            {# Contraseña (opcional, solo si se desea cambiar) #}
            <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Actualizar Contraseña</h2>
            <p class=\"text-sm text-gray-600 mb-4\">Solo cambia la contraseña si deseas actualizarla. Déjala en blanco para mantener la actual.</p>
           {#  <div>{{ form_row(form.user.password, {'label': 'Nueva contraseña'}) }}</div> #}
        </div>

        <div class=\"flex items-center justify-end gap-3 mt-6\">
            <a href=\"{{ path('app_volunteer_list') }}\" class=\"px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors\">Cancelar</a>
            <button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors\">Guardar Cambios</button>
        </div>

        {{ form_end(form) }}
    </div>

    {# INICIO DEL BLOQUE DE JAVASCRIPT - ADAPTADO PARA REEMPLAZAR DIV POR IMG #}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Selecciona el input de archivo por su tipo y parte del atributo 'name'
            const profilePictureInput = document.querySelector('input[type=\"file\"][name\$=\"[profilePicture]\"]');
            // Usamos 'let' para la previsualización porque su referencia podría cambiar (de div a img)
            let previewElement = document.getElementById('preview-element');

            if (profilePictureInput && previewElement) {
                profilePictureInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            // Si el elemento de previsualización actual es un DIV (el \"Sin Foto\"),
                            // lo reemplazamos por una nueva etiqueta IMG.
                            if (previewElement.tagName === 'DIV') {
                                const newImg = document.createElement('img');
                                newImg.id = 'preview-element'; // Mantiene el mismo ID
                                newImg.alt = 'Foto de perfil';
                                // Copia las clases para mantener el estilo circular y responsivo
                                newImg.className = 'w-32 h-32 rounded-full object-cover mx-auto border-2 border-blue-300 shadow mb-4';
                                previewElement.parentNode.replaceChild(newImg, previewElement);
                                previewElement = newImg; // Actualiza la referencia al nuevo elemento IMG
                            }
                            // Establece la fuente de la imagen
                            previewElement.src = e.target.result;
                        };
                        reader.onerror = function(e) {
                            console.error('Error al leer el archivo:', e);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Si no se selecciona ningún archivo o se cancela,
                        // y el elemento actual es una IMG, lo revertimos a un DIV \"Sin Foto\".
                        if (previewElement.tagName === 'IMG') {
                            const newDiv = document.createElement('div');
                            newDiv.id = 'preview-element'; // Mantiene el mismo ID
                            newDiv.className = 'w-32 h-32 rounded-full mx-auto bg-gray-300 flex items-center justify-center text-gray-600 text-sm mb-4 border-2 border-blue-300 shadow';
                            newDiv.textContent = 'Sin Foto';
                            previewElement.parentNode.replaceChild(newDiv, previewElement);
                            previewElement = newDiv; // Actualiza la referencia al nuevo elemento DIV
                        }
                    }
                });
            } else {
                console.error(\"ERROR: Elementos de previsualización o input de archivo no encontrados.\");
            }
        });
    </script>
    {# FIN DEL BLOQUE DE JAVASCRIPT #}
{% endblock %}", "volunteer/edit_volunteer.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\volunteer\\edit_volunteer.html.twig");
    }
}
