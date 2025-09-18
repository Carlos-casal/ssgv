import React from 'react';
import { CheckCircle2, XCircle, User, Trash2 } from 'lucide-react';

interface Volunteer {
    name: string;
    lastName: string;
}

interface Fichaje {
    volunteer: Volunteer;
    startTime: string | null;
    endTime: string | null;
    serviceDate: string;
}

interface AsistenciaListadoProps {
    fichajes: Fichaje[];
}

const AsistenciaListado: React.FC<AsistenciaListadoProps> = ({ fichajes }) => {
    const formatDate = (dateString: string | null) => {
        if (!dateString) return "No fichado.";
        const date = new Date(dateString);
        return date.toLocaleString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const formatHeaderDate = (dateString: string) => {
        const date = new Date(dateString);
         return date.toLocaleString('es-ES', {
            day: 'numeric',
            month: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).replace(',', '');
    };

    return (
        <div className="space-y-4">
            {fichajes.map((fichaje, index) => {
                const isCompleto = fichaje.startTime && fichaje.endTime;
                const volunteerName = `${fichaje.volunteer.name} ${fichaje.volunteer.lastName}`;

                return (
                    <div key={index} className="bg-green-100 p-4 rounded-lg shadow-sm border border-green-200">
                        <div className="flex justify-between items-start">
                            <div>
                                <p className="font-bold text-gray-800">{volunteerName} - {formatHeaderDate(fichaje.serviceDate)}</p>
                                <div className={`flex items-center mt-1 ${isCompleto ? 'text-green-700' : 'text-red-700'}`}>
                                    {isCompleto ? (
                                        <CheckCircle2 className="w-5 h-5 mr-2" />
                                    ) : (
                                        <XCircle className="w-5 h-5 mr-2" />
                                    )}
                                    <p className="font-semibold">{isCompleto ? 'Fichaje completado' : 'Fichaje incompleto'}</p>
                                </div>
                                <p className="text-sm text-gray-600 mt-2">
                                    Entrada: {fichaje.startTime ? formatDate(fichaje.startTime) : 'No fichado.'} - Salida: {fichaje.endTime ? formatDate(fichaje.endTime) : 'No fichado.'}
                                </p>
                            </div>
                            <div className="flex items-center space-x-2">
                                <button className="p-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                                    <User className="w-5 h-5" />
                                </button>
                                <button className="p-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                                    <Trash2 className="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                );
            })}
             {fichajes.length === 0 && (
                <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center">
                    <p className="text-gray-500">No hay fichajes registrados para este servicio.</p>
                </div>
            )}
        </div>
    );
};

export default AsistenciaListado;
