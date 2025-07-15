import { computed } from 'vue';

export function useFormatters() {
  const formatCurrency = (amount: number, currency = 'USD', locale = 'en-US') => {
    return new Intl.NumberFormat(locale, {
      style: 'currency',
      currency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 2,
    }).format(amount);
  };

  const formatNumber = (number: number, locale = 'en-US') => {
    return new Intl.NumberFormat(locale).format(number);
  };

  const formatCompactNumber = (number: number, locale = 'en-US') => {
    return new Intl.NumberFormat(locale, {
      notation: 'compact',
      compactDisplay: 'short',
    }).format(number);
  };

  const formatPercentage = (value: number, decimals = 1) => {
    return `${value.toFixed(decimals)}%`;
  };

  const formatHours = (hours: number) => {
    if (hours < 1) {
      return `${Math.round(hours * 60)}m`;
    }
    return `${hours.toFixed(1)}h`;
  };

  const formatDuration = (days: number) => {
    if (days < 1) {
      return 'Less than a day';
    }
    if (days === 1) {
      return '1 day';
    }
    if (days < 7) {
      return `${Math.round(days)} days`;
    }
    if (days < 30) {
      const weeks = Math.round(days / 7);
      return weeks === 1 ? '1 week' : `${weeks} weeks`;
    }
    if (days < 365) {
      const months = Math.round(days / 30);
      return months === 1 ? '1 month' : `${months} months`;
    }
    const years = Math.round(days / 365);
    return years === 1 ? '1 year' : `${years} years`;
  };

  const formatDate = (date: string | Date, options?: Intl.DateTimeFormatOptions) => {
    const dateObj = typeof date === 'string' ? new Date(date) : date;
    const defaultOptions: Intl.DateTimeFormatOptions = {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    };
    return dateObj.toLocaleDateString('en-US', { ...defaultOptions, ...options });
  };

  const formatDateTime = (date: string | Date) => {
    const dateObj = typeof date === 'string' ? new Date(date) : date;
    return dateObj.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const formatRelativeTime = (date: string | Date) => {
    const dateObj = typeof date === 'string' ? new Date(date) : date;
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - dateObj.getTime()) / 1000);

    if (diffInSeconds < 60) {
      return 'Just now';
    }

    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) {
      return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
    }

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) {
      return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
    }

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) {
      return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
    }

    const diffInWeeks = Math.floor(diffInDays / 7);
    if (diffInWeeks < 4) {
      return `${diffInWeeks} week${diffInWeeks > 1 ? 's' : ''} ago`;
    }

    const diffInMonths = Math.floor(diffInDays / 30);
    if (diffInMonths < 12) {
      return `${diffInMonths} month${diffInMonths > 1 ? 's' : ''} ago`;
    }

    const diffInYears = Math.floor(diffInDays / 365);
    return `${diffInYears} year${diffInYears > 1 ? 's' : ''} ago`;
  };

  const formatFileSize = (bytes: number) => {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  const truncateText = (text: string, maxLength: number, suffix = '...') => {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength - suffix.length) + suffix;
  };

  const capitalizeFirst = (text: string) => {
    return text.charAt(0).toUpperCase() + text.slice(1);
  };

  const formatSlug = (text: string) => {
    return text
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');
  };

  return {
    formatCurrency,
    formatNumber,
    formatCompactNumber,
    formatPercentage,
    formatHours,
    formatDuration,
    formatDate,
    formatDateTime,
    formatRelativeTime,
    formatFileSize,
    truncateText,
    capitalizeFirst,
    formatSlug,
  };
}
