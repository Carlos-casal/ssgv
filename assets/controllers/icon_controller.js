import { Controller } from '@hotwired/stimulus';
import {
    createIcons,
    Home,
    ChevronRight,
    Users,
    Briefcase,
    Megaphone,
    Archive,
    Truck,
    Boxes,
    Puzzle,
    FireHydrant,
    UserPlus,
    Landmark,
    CheckSquare,
    FileArchive,
    PhoneForwarded,
    BarChart2,
    Settings,
    UserCog,
    Star,
    ShieldCheck,
    LogOut,
    CheckCircle,
    AlertCircle,
    Download,
    Plus,
    Search,
    Filter,
    Eye,
    Edit,
    Trash2,
    ArrowUpDown,
    Pencil,
    Mail,
    Lock,
    Construction,
    Calendar,
    Car,
    Clock,
    TrendingUp
} from 'lucide';

/**
 * Controller to render Lucide icons.
 * Attach to the <body> tag.
 */
export default class extends Controller {
    connect() {
        this.render();
    }

    render() {
        try {
            createIcons({
                icons: {
                    Home,
                    ChevronRight,
                    Users,
                    Briefcase,
                    Megaphone,
                    Archive,
                    Truck,
                    Boxes,
                    Puzzle,
                    FireHydrant,
                    UserPlus,
                    Landmark,
                    CheckSquare,
                    FileArchive,
                    PhoneForwarded,
                    BarChart2,
                    Settings,
                    UserCog,
                    Star,
                    ShieldCheck,
                    LogOut,
                    CheckCircle,
                    AlertCircle,
                    Download,
                    Plus,
                    Search,
                    Filter,
                    Eye,
                    Edit,
                    Trash2,
                    ArrowUpDown,
                    Pencil,
                    Mail,
                    Lock,
                    Construction,
                    Calendar,
                    Car,
                    Clock,
                    TrendingUp
                }
            });
        } catch (error) {
            console.error('Error rendering icons:', error);
        }
    }
}
