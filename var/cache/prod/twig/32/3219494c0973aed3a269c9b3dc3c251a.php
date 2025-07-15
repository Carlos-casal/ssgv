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
class __TwigTemplate_47f857e55133db96a49882bf3a9f37eb extends Template
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
        $this->parent = $this->load("layout/app.html.twig", 3);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Editar Voluntario";
        yield from [];
    }

    // line 7
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 8
        yield "    <div class=\"container mx-auto p-6\">
        <h1 class=\"text-2xl font-bold mb-6 text-gray-900\">Editar Voluntario</h1>

        ";
        // line 12
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "flashes", ["success"], "method", false, false, false, 12));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 13
            yield "            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">";
            // line 14
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "flashes", ["error"], "method", false, false, false, 17));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 18
            yield "            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">";
            // line 19
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        yield "
        ";
        // line 23
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["form"] ?? null), 'form_start', ["attr" => ["class" => "bg-white p-6 rounded-lg shadow-md"], "enctype" => "multipart/form-data"]);
        yield "

        <div class=\"flex flex-col md:flex-row gap-6 mb-8\">
            ";
        // line 27
        yield "            <div class=\"flex-shrink-0 w-full md:w-1/3 p-4 border border-gray-200 rounded-lg text-center bg-gray-50\">
                <h3 class=\"text-xl font-semibold mb-4 text-gray-800\">Foto de Perfil</h3>
                <div class=\"mb-4\">
                    ";
        // line 30
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "profilePicture", [], "any", false, false, false, 30)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 31
            yield "                        <img src=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl(("uploads/profile_pictures/" . CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "profilePicture", [], "any", false, false, false, 31))), "html", null, true);
            yield "\"
                             alt=\"Foto de Perfil\"
                             class=\"w-32 h-32 rounded-full mx-auto object-cover border-2 border-blue-300 shadow-md mb-4\">
                    ";
        } else {
            // line 35
            yield "                        <div class=\"w-32 h-32 rounded-full mx-auto bg-gray-300 flex items-center justify-center text-gray-600 text-sm mb-4\">
                            Sin Foto
                        </div>
                    ";
        }
        // line 39
        yield "                    ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "profilePicture", [], "any", false, false, false, 39), 'row', ["label" => "Cambiar foto", "attr" => ["class" => "block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"]]);
        // line 42
        yield "
                    <p class=\"text-xs text-gray-500 mt-1\">Sube una imagen JPG o PNG (máx. 1MB).</p>
                </div>
                ";
        // line 46
        yield "                <div class=\"text-sm text-gray-700 mt-4\">
                    <p><strong class=\"block text-gray-600 text-xs\">Nombre Completo:</strong> ";
        // line 47
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "name", [], "any", false, false, false, 47), "html", null, true);
        yield " ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "lastName", [], "any", false, false, false, 47), "html", null, true);
        yield "</p>
                    <p><strong class=\"block text-gray-600 text-xs\">DNI:</strong> ";
        // line 48
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "dni", [], "any", false, false, false, 48), "html", null, true);
        yield "</p>
                    <p><strong class=\"block text-gray-600 text-xs\">Email:</strong> ";
        // line 49
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "user", [], "any", false, false, false, 49), "email", [], "any", false, false, false, 49), "html", null, true);
        yield "</p>
                    <p><strong class=\"block text-gray-600 text-xs\">Teléfono:</strong> ";
        // line 50
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "phone", [], "any", false, false, false, 50), "html", null, true);
        yield "</p>
                </div>
            </div>

            ";
        // line 55
        yield "            <div class=\"flex-grow w-full md:w-2/3 p-4 border border-gray-200 rounded-lg bg-white\">
                <h3 class=\"text-xl font-semibold mb-4 text-gray-800\">Datos del Voluntario</h3>

                ";
        // line 59
        yield "                <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Datos Personales</h2>
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                    <div>";
        // line 61
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "name", [], "any", false, false, false, 61), 'row');
        yield "</div>
                    <div>";
        // line 62
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "lastName", [], "any", false, false, false, 62), 'row');
        yield "</div>
                    <div>";
        // line 63
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dni", [], "any", false, false, false, 63), 'row');
        yield "</div>
                    <div>";
        // line 64
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dateOfBirth", [], "any", false, false, false, 64), 'row');
        yield "</div>
                    <div>";
        // line 65
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "phone", [], "any", false, false, false, 65), 'row');
        yield "</div>
                    <div>";
        // line 66
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 66), "email", [], "any", false, false, false, 66), 'row');
        yield "</div> ";
        // line 67
        yield "                </div>

                ";
        // line 70
        yield "                <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Dirección</h2>
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                    <div>";
        // line 72
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "streetType", [], "any", false, false, false, 72), 'row');
        yield "</div>
                    <div>";
        // line 73
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "address", [], "any", false, false, false, 73), 'row');
        yield "</div>
                    <div>";
        // line 74
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "postalCode", [], "any", false, false, false, 74), 'row');
        yield "</div>
                    <div>";
        // line 75
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "province", [], "any", false, false, false, 75), 'row');
        yield "</div>
                    <div>";
        // line 76
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "city", [], "any", false, false, false, 76), 'row');
        yield "</div>
                </div>

                ";
        // line 80
        yield "                <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Información de Emergencia</h2>
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                    <div>";
        // line 82
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "emergencyContactName", [], "any", false, false, false, 82), 'row');
        yield "</div>
                    <div>";
        // line 83
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "emergencyContactPhone", [], "any", false, false, false, 83), 'row');
        yield "</div>
                    ";
        // line 85
        yield "                    ";
        // line 86
        yield "                    ";
        // line 87
        yield "                </div>

                ";
        // line 90
        yield "                <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Información Médica</h2>
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                    <div>";
        // line 92
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "bloodType", [], "any", false, false, false, 92), 'row');
        yield "</div>
                    <div>";
        // line 93
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "allergies", [], "any", false, false, false, 93), 'row');
        yield "</div>
                    <div>";
        // line 94
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "medicalConditions", [], "any", false, false, false, 94), 'row');
        yield "</div>
                </div>

                ";
        // line 98
        yield "                <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Profesional y Cualificaciones</h2>
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                    <div>";
        // line 100
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "profession", [], "any", false, false, false, 100), 'row');
        yield "</div>
                    <div>";
        // line 101
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "employmentStatus", [], "any", false, false, false, 101), 'row');
        yield "</div>
                    <div>";
        // line 102
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "drivingLicenses", [], "any", false, false, false, 102), 'row');
        yield "</div>
                    <div>";
        // line 103
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "drivingLicenseExpiryDate", [], "any", false, false, false, 103), 'row');
        yield "</div>
                </div>
                <div class=\"mb-4\">
                    ";
        // line 106
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "navigationLicenses", [], "any", false, false, false, 106), 'row');
        yield "
                </div>
                <div class=\"mb-4\">
                    ";
        // line 109
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "specificQualifications", [], "any", false, false, false, 109), 'row');
        yield "
                </div>
                <div class=\"mb-4\">
                    ";
        // line 112
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "otherQualifications", [], "any", false, false, false, 112), 'row');
        yield "
                </div>

                ";
        // line 116
        yield "                <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Otros Datos e Intereses</h2>
                <div class=\"mb-4\">
                    ";
        // line 118
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "languages", [], "any", false, false, false, 118), 'row');
        yield "
                </div>
                <div class=\"mb-4\">
                    ";
        // line 121
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "motivation", [], "any", false, false, false, 121), 'row');
        yield "
                </div>
                <div class=\"mb-4\">
                    ";
        // line 124
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "howKnown", [], "any", false, false, false, 124), 'row');
        yield "
                </div>
                <div class=\"mb-4\">
                    ";
        // line 127
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "hasVolunteeredBefore", [], "any", false, false, false, 127), 'row');
        yield "
                </div>
                <div class=\"mb-4\">
                    ";
        // line 130
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "previousVolunteeringInstitutions", [], "any", false, false, false, 130), 'row');
        yield "
                </div>

                ";
        // line 134
        yield "                <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Rol y Especialización</h2>
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                    <div>";
        // line 136
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "role", [], "any", false, false, false, 136), 'row');
        yield "</div>
                    <div>";
        // line 137
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "specialization", [], "any", false, false, false, 137), 'row');
        yield "</div>
                </div>

                ";
        // line 141
        yield "                <h2 class=\"text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2\">Actualizar Contraseña</h2>
                <p class=\"text-sm text-gray-600 mb-4\">Solo cambia la contraseña si deseas actualizarla. Déjala en blanco para mantener la actual.</p>
                <div>";
        // line 143
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 143), "password", [], "any", false, false, false, 143), 'row');
        yield "</div>
            </div>
        </div>

        <div class=\"flex items-center justify-end gap-3 mt-6\">
            <a href=\"";
        // line 148
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_list");
        yield "\" class=\"px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors\">Cancelar</a>
            <button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors\">Guardar Cambios</button>
        </div>

        ";
        // line 152
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["form"] ?? null), 'form_end');
        yield "
    </div>
";
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
        return array (  378 => 152,  371 => 148,  363 => 143,  359 => 141,  353 => 137,  349 => 136,  345 => 134,  339 => 130,  333 => 127,  327 => 124,  321 => 121,  315 => 118,  311 => 116,  305 => 112,  299 => 109,  293 => 106,  287 => 103,  283 => 102,  279 => 101,  275 => 100,  271 => 98,  265 => 94,  261 => 93,  257 => 92,  253 => 90,  249 => 87,  247 => 86,  245 => 85,  241 => 83,  237 => 82,  233 => 80,  227 => 76,  223 => 75,  219 => 74,  215 => 73,  211 => 72,  207 => 70,  203 => 67,  200 => 66,  196 => 65,  192 => 64,  188 => 63,  184 => 62,  180 => 61,  176 => 59,  171 => 55,  164 => 50,  160 => 49,  156 => 48,  150 => 47,  147 => 46,  142 => 42,  139 => 39,  133 => 35,  125 => 31,  123 => 30,  118 => 27,  112 => 23,  109 => 22,  100 => 19,  97 => 18,  92 => 17,  83 => 14,  80 => 13,  75 => 12,  70 => 8,  63 => 7,  52 => 5,  41 => 3,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "volunteer/edit_volunteer.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\volunteer\\edit_volunteer.html.twig");
    }
}
