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

/* volunteer/edit.volunterr.html.twig */
class __TwigTemplate_fc790e2bdd8bb28254a7dfb54823acfd extends Template
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
            'stylesheets' => [$this, 'block_stylesheets'],
            'body' => [$this, 'block_body'],
            'javascripts' => [$this, 'block_javascripts'],
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
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Editar Voluntario";
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_stylesheets(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "    ";
        yield from $this->yieldParentBlock("stylesheets", $context, $blocks);
        yield "
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 8
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("fonts/fontawesome-free-5.8.1-web/css/all.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 9
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/jquery-confirm.min.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css\">
    <link href=\"https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css\" rel=\"stylesheet\" />
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css\" />
    <style>
        body {
            background-color: #f8f9fa;
            font-size: 15px !important;
            font-weight: normal !important;
        }
        .pcam-caja {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .pcam-btn-verde, .pcam-btn-rojo, .pcam-btn-gris {
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            border: none;
        }
        .pcam-btn-verde { background-color: #28a745; } /* Green */
        .pcam-btn-rojo { background-color: #dc3545; } /* Red */
        .pcam-btn-gris { background-color: #6c757d; } /* Grey */

        .pcam-btn-verde:hover { background-color: #218838; }
        .pcam-btn-rojo:hover { background-color: #c82333; }
        .pcam-btn-gris:hover { background-color: #5a6268; }

        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #f8f9fa;
            border-color: #dee2e6 #dee2e6 #f8f9fa;
        }
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            color: #007bff;
        }
        .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
        }
        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1em;
        }
    </style>
";
        yield from [];
    }

    // line 65
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 66
        yield "<div class=\"container-fluid\">
    <div class=\"pcam-caja\">
        <h2 class=\"text-3xl font-bold mb-4\">Editar Voluntario</h2>

        <ul class=\"nav nav-tabs\" id=\"myTab\" role=\"tablist\">
            <li class=\"nav-item\">
                <a class=\"nav-link active\" id=\"datos-personales-tab\" data-toggle=\"tab\" href=\"#datos-personales\" role=\"tab\" aria-controls=\"datos-personales\" aria-selected=\"true\">
                    <i class=\"fas fa-id-card\"></i> Datos Personales
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" id=\"filiacion-tab\" data-toggle=\"tab\" href=\"#filiacion\" role=\"tab\" aria-controls=\"filiacion\" aria-selected=\"false\">
                    <i class=\"fas fa-sitemap\"></i> Filiación
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" id=\"formacion-tab\" data-toggle=\"tab\" href=\"#formacion\" role=\"tab\" aria-controls=\"formacion\" aria-selected=\"false\">
                    <i class=\"fas fa-graduation-cap\"></i> Formación
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" id=\"informes-tab\" data-toggle=\"tab\" href=\"#informes\" role=\"tab\" aria-controls=\"informes\" aria-selected=\"false\">
                    <i class=\"fas fa-chart-pie\"></i> Informes
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" id=\"anotaciones-tab\" data-toggle=\"tab\" href=\"#anotaciones\" role=\"tab\" aria-controls=\"anotaciones\" aria-selected=\"false\">
                    <i class=\"fas fa-comments\"></i> Anotaciones
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" id=\"historico-tab\" data-toggle=\"tab\" href=\"#historico\" role=\"tab\" aria-controls=\"historico\" aria-selected=\"false\">
                    <i class=\"fas fa-history\"></i> Histórico
                </a>
            </li>
        </ul>

        <div class=\"tab-content\" id=\"myTabContent\">
            <div class=\"tab-pane fade show active\" id=\"datos-personales\" role=\"tabpanel\" aria-labelledby=\"datos-personales-tab\">
                ";
        // line 105
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["form"] ?? null), 'form_start');
        yield "
                <div class=\"row mt-3\">
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 109
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "name", [], "any", false, false, false, 109), 'label', ["label" => "Nombre"]);
        yield "
                            ";
        // line 110
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "name", [], "any", false, false, false, 110), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 25, "placeholder" => "Ej: Juan"]]);
        yield "
                            ";
        // line 111
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "name", [], "any", false, false, false, 111), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 116
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "lastName", [], "any", false, false, false, 116), 'label', ["label" => "Apellidos"]);
        yield "
                            ";
        // line 117
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "lastName", [], "any", false, false, false, 117), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 25, "placeholder" => "Ej: García López"]]);
        yield "
                            ";
        // line 118
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "lastName", [], "any", false, false, false, 118), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-3\">
                        <div class=\"form-group\">
                            ";
        // line 123
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "phone", [], "any", false, false, false, 123), 'label', ["label" => "Teléfono"]);
        yield "
                            ";
        // line 124
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "phone", [], "any", false, false, false, 124), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 12, "placeholder" => "Ej: 600123456", "type" => "tel"]]);
        yield "
                            ";
        // line 125
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "phone", [], "any", false, false, false, 125), 'errors');
        yield "
                        </div>
                    </div>
                </div>

                <div class=\"row\">
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 133
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dni", [], "any", false, false, false, 133), 'label', ["label" => "DNI"]);
        yield "
                            ";
        // line 134
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dni", [], "any", false, false, false, 134), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 9, "placeholder" => "Ej: 12345678A"]]);
        yield "
                            ";
        // line 135
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dni", [], "any", false, false, false, 135), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 140
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dateOfBirth", [], "any", false, false, false, 140), 'label', ["label" => "Fecha de Nacimiento"]);
        yield "
                            ";
        // line 141
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dateOfBirth", [], "any", false, false, false, 141), 'widget', ["attr" => ["class" => "form-control"]]);
        yield "
                            ";
        // line 142
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dateOfBirth", [], "any", false, false, false, 142), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-3\">
                        <div class=\"form-group\">
                            ";
        // line 147
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "gender", [], "any", false, false, false, 147), 'label', ["label" => "Sexo"]);
        yield "
                            ";
        // line 148
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "gender", [], "any", false, false, false, 148), 'widget', ["attr" => ["class" => "form-control"]]);
        yield "
                            ";
        // line 149
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "gender", [], "any", false, false, false, 149), 'errors');
        yield "
                        </div>
                    </div>
                </div>

                <div class=\"row\">
                    <div class=\"col-lg-3\">
                        <div class=\"form-group\">
                            ";
        // line 157
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "streetType", [], "any", false, false, false, 157), 'label', ["label" => "Tipo de Vía"]);
        yield "
                            ";
        // line 158
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "streetType", [], "any", false, false, false, 158), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 50, "placeholder" => "Ej: Calle, Avenida"]]);
        yield "
                            ";
        // line 159
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "streetType", [], "any", false, false, false, 159), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-6\">
                        <div class=\"form-group\">
                            ";
        // line 164
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "address", [], "any", false, false, false, 164), 'label', ["label" => "Dirección"]);
        yield "
                            ";
        // line 165
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "address", [], "any", false, false, false, 165), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 255, "placeholder" => "Ej: Falsa 123"]]);
        yield "
                            ";
        // line 166
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "address", [], "any", false, false, false, 166), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-2\">
                        <div class=\"form-group\">
                            ";
        // line 171
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "postalCode", [], "any", false, false, false, 171), 'label', ["label" => "Código Postal"]);
        yield "
                            ";
        // line 172
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "postalCode", [], "any", false, false, false, 172), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 10, "placeholder" => "Ej: 28001"]]);
        yield "
                            ";
        // line 173
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "postalCode", [], "any", false, false, false, 173), 'errors');
        yield "
                        </div>
                    </div>
                </div>

                <div class=\"row\">
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 181
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "province", [], "any", false, false, false, 181), 'label', ["label" => "Provincia"]);
        yield "
                            ";
        // line 182
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "province", [], "any", false, false, false, 182), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 100, "placeholder" => "Ej: Madrid"]]);
        yield "
                            ";
        // line 183
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "province", [], "any", false, false, false, 183), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 188
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "city", [], "any", false, false, false, 188), 'label', ["label" => "Localidad"]);
        yield "
                            ";
        // line 189
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "city", [], "any", false, false, false, 189), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 100, "placeholder" => "Ej: Madrid"]]);
        yield "
                            ";
        // line 190
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "city", [], "any", false, false, false, 190), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 195
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "nationality", [], "any", false, false, false, 195), 'label', ["label" => "Nacionalidad"]);
        yield "
                            ";
        // line 196
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "nationality", [], "any", false, false, false, 196), 'widget', ["attr" => ["class" => "form-control", "maxlength" => 100, "placeholder" => "Ej: Española"]]);
        yield "
                            ";
        // line 197
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "nationality", [], "any", false, false, false, 197), 'errors');
        yield "
                        </div>
                    </div>
                </div>

                <div class=\"row\">
                    <div class=\"col-lg-12\">
                        <div class=\"form-group\">
                            ";
        // line 205
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "notes", [], "any", false, false, false, 205), 'label', ["label" => "Notas"]);
        yield "
                            ";
        // line 206
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "notes", [], "any", false, false, false, 206), 'widget', ["attr" => ["class" => "form-control", "rows" => 3, "placeholder" => "Información adicional sobre el voluntario."]]);
        yield "
                            ";
        // line 207
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "notes", [], "any", false, false, false, 207), 'errors');
        yield "
                        </div>
                    </div>
                </div>

                <div class=\"row\">
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 215
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "status", [], "any", false, false, false, 215), 'label', ["label" => "Estado del Voluntario"]);
        yield "
                            ";
        // line 216
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "status", [], "any", false, false, false, 216), 'widget', ["attr" => ["class" => "form-control"]]);
        yield "
                            ";
        // line 217
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "status", [], "any", false, false, false, 217), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 222
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "role", [], "any", false, false, false, 222), 'label', ["label" => "Rol en la Organización"]);
        yield "
                            ";
        // line 223
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "role", [], "any", false, false, false, 223), 'widget', ["attr" => ["class" => "form-control"]]);
        yield "
                            ";
        // line 224
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "role", [], "any", false, false, false, 224), 'errors');
        yield "
                        </div>
                    </div>
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 229
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "specialization", [], "any", false, false, false, 229), 'label', ["label" => "Especialización (Opcional)"]);
        yield "
                            ";
        // line 230
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "specialization", [], "any", false, false, false, 230), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "Ej: Primeros Auxilios, Cocina"]]);
        yield "
                            ";
        // line 231
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "specialization", [], "any", false, false, false, 231), 'errors');
        yield "
                        </div>
                    </div>
                </div>

                <div class=\"row mt-3\">
                    <div class=\"col-lg-12\">
                        <h4>Datos de Acceso (Usuario)</h4>
                        <hr>
                    </div>
                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 243
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 243), "email", [], "any", false, false, false, 243), 'label', ["label" => "Email de Usuario"]);
        yield "
                            ";
        // line 244
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 244), "email", [], "any", false, false, false, 244), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "Ej: usuario@example.com", "type" => "email"]]);
        yield "
                            ";
        // line 245
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 245), "email", [], "any", false, false, false, 245), 'errors');
        yield "
                        </div>
                    </div>
                    ";
        // line 249
        yield "                    ";
        // line 250
        yield "                    <div class=\"col-lg-4\">
                        <div class=\"form-group\">
                            ";
        // line 252
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 252), "password", [], "any", false, false, false, 252), 'label', ["label" => "Nueva Contraseña (Dejar en blanco para no cambiar)"]);
        yield "
                            ";
        // line 253
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 253), "password", [], "any", false, false, false, 253), 'widget', ["attr" => ["class" => "form-control", "type" => "password"]]);
        yield "
                            ";
        // line 254
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 254), "password", [], "any", false, false, false, 254), 'errors');
        yield "
                        </div>
                    </div>
                </div>

                <div class=\"row mt-3\">
                    <div class=\"col-lg-12\">
                        <h4>Foto de Perfil</h4>
                        <hr>
                        <div class=\"form-group\">
                            ";
        // line 265
        yield "                            <label for=\"profile_picture\">Subir Foto de Perfil:</label>
                            <input type=\"file\" class=\"form-control-file\" id=\"profile_picture\" name=\"profile_picture_upload\">
                            ";
        // line 268
        yield "                            ";
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "profilePicture", [], "any", false, false, false, 268)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 269
            yield "                                <img src=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl(("uploads/profile_pictures/" . CoreExtension::getAttribute($this->env, $this->source, ($context["volunteer"] ?? null), "profilePicture", [], "any", false, false, false, 269))), "html", null, true);
            yield "\" alt=\"Foto de Perfil Actual\" class=\"img-thumbnail mt-2\" style=\"max-width: 150px;\">
                            ";
        }
        // line 271
        yield "                        </div>
                    </div>
                </div>

                <div class=\"row mt-4\">
                    <div class=\"col-lg-12\">
                        <button type=\"submit\" class=\"pcam-btn-verde mr-2\"><i class=\"fas fa-save\"></i> Guardar Cambios</button>
                        <a href=\"";
        // line 278
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_list");
        yield "\" class=\"pcam-btn-gris\"><i class=\"fas fa-times-circle\"></i> Cancelar</a>
                    </div>
                </div>
                ";
        // line 281
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["form"] ?? null), 'form_end');
        yield "
            </div>

            <div class=\"tab-pane fade\" id=\"filiacion\" role=\"tabpanel\" aria-labelledby=\"filiacion-tab\">
                <div class=\"alert alert-info mt-3\" role=\"alert\">
                    Contenido de la sección de Filiación. Aquí irían los campos para gestionar la filiación del voluntario.
                    <br>
                    Según el HTML original, esta sección podría incluir búsqueda y creación de filiaciones.
                </div>
                ";
        // line 291
        yield "            </div>

            <div class=\"tab-pane fade\" id=\"formacion\" role=\"tabpanel\" aria-labelledby=\"formacion-tab\">
                <div class=\"alert alert-info mt-3\" role=\"alert\">
                    Contenido de la sección de Formación. Aquí se listarían y gestionarían los cursos y titulaciones del voluntario.
                    <br>
                    Según el HTML original, esta sección incluiría una tabla de cursos y modales para añadir/editar.
                </div>
                ";
        // line 300
        yield "            </div>

            <div class=\"tab-pane fade\" id=\"informes\" role=\"tabpanel\" aria-labelledby=\"informes-tab\">
                <div class=\"alert alert-info mt-3\" role=\"alert\">
                    Contenido de la sección de Informes.
                </div>
                <a href=\"";
        // line 306
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_reports");
        yield "\" class=\"pcam-btn-gris mt-3\"><i class=\"fas fa-chart-pie\"></i> Ir a informes</a>
            </div>

            <div class=\"tab-pane fade\" id=\"anotaciones\" role=\"tabpanel\" aria-labelledby=\"anotaciones-tab\">
                <div class=\"alert alert-info mt-3\" role=\"alert\">
                    Contenido de la sección de Anotaciones. Aquí se gestionarían las notas y comentarios sobre el voluntario.
                    <br>
                    Según el HTML original, esta sección incluiría una tabla de anotaciones y un modal para añadir.
                </div>
                ";
        // line 316
        yield "            </div>

            <div class=\"tab-pane fade\" id=\"historico\" role=\"tabpanel\" aria-labelledby=\"historico-tab\">
                <div class=\"alert alert-primary mt-3\" role=\"alert\">
                    En esta sección podrás visualizar las diferentas altas y bajas que ha causado esta persona en la agrupación.
                </div>
                <div class=\"alert alert-info mt-3\" role=\"alert\">
                    Contenido de la sección de Histórico.
                </div>
                ";
        // line 326
        yield "            </div>
        </div>
    </div>
</div>
";
        yield from [];
    }

    // line 332
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 333
        yield "    ";
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield "
    <script src=\"https://code.jquery.com/jquery-3.4.1.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js\"></script>
    <script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js\"></script>
    <script src=\"";
        // line 337
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/jquery-confirm.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js\"></script>
    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js\"></script>
    ";
        // line 342
        yield "    <script>
        \$(function () {
            \$('#myTab a').on('click', function (e) {
                e.preventDefault();
                \$(this).tab('show');
            });
            // Show toastr messages if any (you would integrate Symfony Flash messages here)
            // Example: toastr.success('Voluntario actualizado correctamente!');
        });
    </script>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "volunteer/edit.volunterr.html.twig";
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
        return array (  628 => 342,  621 => 337,  613 => 333,  606 => 332,  597 => 326,  586 => 316,  574 => 306,  566 => 300,  556 => 291,  544 => 281,  538 => 278,  529 => 271,  523 => 269,  520 => 268,  516 => 265,  503 => 254,  499 => 253,  495 => 252,  491 => 250,  489 => 249,  483 => 245,  479 => 244,  475 => 243,  460 => 231,  456 => 230,  452 => 229,  444 => 224,  440 => 223,  436 => 222,  428 => 217,  424 => 216,  420 => 215,  409 => 207,  405 => 206,  401 => 205,  390 => 197,  386 => 196,  382 => 195,  374 => 190,  370 => 189,  366 => 188,  358 => 183,  354 => 182,  350 => 181,  339 => 173,  335 => 172,  331 => 171,  323 => 166,  319 => 165,  315 => 164,  307 => 159,  303 => 158,  299 => 157,  288 => 149,  284 => 148,  280 => 147,  272 => 142,  268 => 141,  264 => 140,  256 => 135,  252 => 134,  248 => 133,  237 => 125,  233 => 124,  229 => 123,  221 => 118,  217 => 117,  213 => 116,  205 => 111,  201 => 110,  197 => 109,  190 => 105,  149 => 66,  142 => 65,  82 => 9,  78 => 8,  72 => 6,  65 => 5,  54 => 3,  43 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "volunteer/edit.volunterr.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\volunteer\\edit.volunterr.html.twig");
    }
}
