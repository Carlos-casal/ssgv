import React from 'react';
import { Construction, ArrowLeft } from 'lucide-react';

interface ComingSoonProps {
  title: string;
  description?: string;
}

const ComingSoon: React.FC<ComingSoonProps> = ({ title, description }) => {
  return (
    <div className="p-6">
      <div className="max-w-2xl mx-auto text-center">
        <div className="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-6">
          <Construction className="w-8 h-8 text-blue-600" />
        </div>
        
        <h2 className="text-2xl font-bold text-gray-900 mb-4">{title}</h2>
        
        <p className="text-gray-600 mb-8">
          {description || 'Esta sección está en desarrollo. Próximamente estará disponible con todas las funcionalidades necesarias.'}
        </p>

        <div className="bg-blue-50 border border-blue-200 rounded-lg p-6">
          <h3 className="font-semibold text-blue-900 mb-2">¿Qué encontrarás aquí?</h3>
          <ul className="text-sm text-blue-800 space-y-1">
            <li>• Gestión completa de datos</li>
            <li>• Informes y estadísticas detalladas</li>
            <li>• Exportación de información</li>
            <li>• Interface intuitiva y moderna</li>
          </ul>
        </div>

        <div className="mt-8 p-4 bg-gray-50 rounded-lg">
          <p className="text-sm text-gray-600">
            <strong>Nota:</strong> El sistema está siendo desarrollado por módulos. 
            Cada sección será implementada con todas las funcionalidades requeridas.
          </p>
        </div>
      </div>
    </div>
  );
};

export default ComingSoon;