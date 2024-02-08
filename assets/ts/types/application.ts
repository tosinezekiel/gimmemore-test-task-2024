export interface IToken {
    token: string;
}

export interface IUser {
    first_name: string;
    last_name: string;
    email: string;
    full_name: string;
}

export interface IAuthUser extends IUser, IToken {
    
}

export interface IState {
    user: IAuthUser | null;
    loggedIn: boolean;
}

export interface ILoginCredentials {
    email: string;
    password: string;
}

export interface IRegisterCredentials {
    email: string;
    password: string;
    password_confirmation: string;
    first_name: string;
    last_name: string;
}

export interface IBook {
    id: string;
    title: string;
    genre: string;
    author: string;
    created_at: string;
    page_count: string;
    status: string;
    total_read: string;
    pages_left: string;
    slug: string;
    user: IUser;
}

export interface IRequestBook {
    title: string;
    genre: string;
    author: string;
    page_count: string;
}

export interface IReadBook {
    pagesRead: string;
}

export interface IReviewBook {
    rating: number;
    comment: string
}
  