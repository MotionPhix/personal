export interface DashboardStats {
  customers_count: number;
  projects_count: number;
  downloads_count: number;
  subscribers_count: number;
}

export interface ProjectStats {
  total_projects: number;
  active_projects: number;
  completed_projects: number;
  featured_projects: number;
  public_projects: number;
  overdue_projects: number;
  projects_by_status: Record<string, number>;
  projects_by_production_type: Record<string, number>;
  average_project_duration: number;
  total_estimated_hours: number;
  total_actual_hours: number;
  total_budget: number;
}

export interface CustomerStats {
  total_customers: number;
  active_customers: number;
  customers_with_projects: number;
  customers_by_status: Record<string, number>;
  top_customers_by_projects: Array<{
    first_name: string;
    last_name: string;
    company_name: string;
    projects_count: number;
  }>;
}

export interface RecentCustomer {
  cid: string;
  name: string;
  company: string;
  status: string;
  created_at: string;
  avatar: string;
}

export interface RecentProject {
  pid: string;
  name: string;
  status: string;
  priority: string;
  progress: number;
  customer: {
    name: string;
    company: string;
  };
  created_at: string;
  status_color: string;
  priority_color: string;
}

export interface ActivityItem {
  type: string;
  title: string;
  subtitle: string;
  time: string;
  icon: string;
  color: string;
}

export interface TrendData {
  date: string;
  label: string;
  projects: number;
  customers: number;
  subscribers: number;
}

export interface MonthlyComparison {
  current: Record<string, number>;
  previous: Record<string, number>;
  changes: Record<string, number>;
}

export interface Trends {
  daily: TrendData[];
  monthly_comparison: MonthlyComparison;
}

export interface UpcomingDeadline {
  pid: string;
  name: string;
  end_date: string;
  days_left: number;
  customer: string;
  priority: string;
  priority_color: string;
}

export interface ProjectInsights {
  status_distribution: Record<string, number>;
  priority_distribution: Record<string, number>;
  production_types: string[];
  categories: string[];
  overdue_projects: number;
  upcoming_deadlines: UpcomingDeadline[];
}

export interface PerformanceMetrics {
  total_estimated_hours: number;
  total_actual_hours: number;
  total_budget: number;
  hours_variance: number;
  efficiency_rate: number;
  average_project_duration: number;
  completion_rate: number;
}

export interface QuickAction {
  title: string;
  description: string;
  route: string;
  icon: string;
  color: string;
}

export interface Notification {
  type: 'info' | 'warning' | 'error' | 'success';
  title: string;
  message: string;
  action?: string;
  action_text?: string;
}

export interface DashboardData {
  stats: DashboardStats;
  project_stats: ProjectStats;
  customer_stats: CustomerStats;
  recent_customers: RecentCustomer[];
  recent_projects: RecentProject[];
  recent_activity: ActivityItem[];
  trends: Trends;
  project_insights: ProjectInsights;
  performance_metrics: PerformanceMetrics;
  quick_actions: QuickAction[];
  notifications: Notification[];
}
